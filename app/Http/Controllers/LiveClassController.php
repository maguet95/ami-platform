<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\LiveClassAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveClassController extends Controller
{
    public function calendar()
    {
        $user = Auth::user();

        $attendanceClassIds = LiveClassAttendance::where('user_id', $user->id)
            ->pluck('live_class_id');

        $classes = LiveClass::whereIn('id', $attendanceClassIds)
            ->where('status', '!=', 'cancelled')
            ->where('starts_at', '>=', now()->startOfMonth()->subMonth())
            ->with(['course', 'instructor'])
            ->orderBy('starts_at')
            ->get();

        $upcomingClasses = $classes->where('starts_at', '>=', now())
            ->where('status', 'scheduled')
            ->take(5);

        return view('live-classes.calendar', compact('classes', 'upcomingClasses'));
    }

    public function show(LiveClass $liveClass)
    {
        $user = Auth::user();

        $attendance = LiveClassAttendance::where('live_class_id', $liveClass->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $attendance) {
            abort(403, 'No estas registrado en esta clase.');
        }

        $liveClass->load(['course', 'instructor']);

        return view('live-classes.show', compact('liveClass', 'attendance'));
    }

    public function join(LiveClass $liveClass, Request $request)
    {
        $token = $request->query('token');

        if (! $token) {
            abort(403, 'Token de acceso requerido.');
        }

        $attendance = LiveClassAttendance::where('live_class_id', $liveClass->id)
            ->where('access_token', $token)
            ->first();

        if (! $attendance) {
            abort(403, 'Token de acceso invalido.');
        }

        $user = Auth::user();

        if ($attendance->user_id !== $user->id) {
            abort(403, 'Este enlace no te pertenece.');
        }

        if (! $liveClass->isTokenValid()) {
            abort(410, 'Este enlace ha expirado.');
        }

        if ($liveClass->status === 'cancelled') {
            return redirect()->route('live-classes.show', $liveClass)
                ->with('error', 'Esta clase ha sido cancelada.');
        }

        $attendance->markAttended();

        return redirect()->away($liveClass->meeting_url);
    }

    public function triggerNotifications(Request $request)
    {
        $result = \Artisan::call('class:send-reminders');

        return response()->json([
            'status' => 'ok',
            'output' => \Artisan::output(),
        ]);
    }
}
