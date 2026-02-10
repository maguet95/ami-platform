<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    public function index()
    {
        $users = User::where('is_profile_public', true)
            ->where('total_xp', '>', 0)
            ->orderByDesc('total_xp')
            ->limit(100)
            ->get();

        $currentUser = Auth::user();

        return view('pages.ranking', compact('users', 'currentUser'));
    }
}
