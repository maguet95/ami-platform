<?php

namespace App\Http\Controllers;

use App\Models\BrokerConnection;
use App\Services\CsvTradeImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrokerConnectionController extends Controller
{
    public function index()
    {
        if (! Auth::user()->hasPremiumAccess()) {
            return redirect()->route('journal')->with('error', 'Necesitas una suscripcion activa.');
        }

        $connections = Auth::user()->brokerConnections()
            ->latest()
            ->get();

        return view('journal.connections', compact('connections'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasPremiumAccess()) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:binance,metatrader4,metatrader5',
        ]);

        $credentialRules = match ($request->input('type')) {
            'binance' => [
                'api_key' => 'required|string|min:10',
                'api_secret' => 'required|string|min:10',
            ],
            'metatrader4', 'metatrader5' => [
                'login' => 'required|string',
                'password' => 'required|string',
                'server' => 'required|string',
            ],
        };

        $request->validate($credentialRules);

        $credentials = match ($request->input('type')) {
            'binance' => json_encode([
                'api_key' => $request->input('api_key'),
                'api_secret' => $request->input('api_secret'),
            ]),
            'metatrader4', 'metatrader5' => json_encode([
                'login' => $request->input('login'),
                'password' => $request->input('password'),
                'server' => $request->input('server'),
            ]),
        };

        Auth::user()->brokerConnections()->create([
            'type' => $request->input('type'),
            'credentials' => $credentials,
            'status' => BrokerConnection::STATUS_ACTIVE,
            'sync_enabled' => true,
        ]);

        return redirect()->route('journal.connections')
            ->with('success', 'Conexion creada exitosamente.');
    }

    public function destroy(BrokerConnection $connection)
    {
        if ($connection->user_id !== Auth::id()) {
            abort(403);
        }

        $connection->delete();

        return redirect()->route('journal.connections')
            ->with('success', 'Conexion eliminada.');
    }

    public function toggleSync(BrokerConnection $connection)
    {
        if ($connection->user_id !== Auth::id()) {
            abort(403);
        }

        $connection->update(['sync_enabled' => ! $connection->sync_enabled]);

        $msg = $connection->sync_enabled ? 'Sincronizacion activada.' : 'Sincronizacion desactivada.';

        return redirect()->route('journal.connections')
            ->with('success', $msg);
    }

    public function uploadCsv(Request $request)
    {
        if (! Auth::user()->hasPremiumAccess()) {
            abort(403);
        }

        $maxSize = config('journal.csv_max_file_size', 5120);

        $request->validate([
            'csv_file' => "required|file|max:{$maxSize}",
            'csv_format' => 'required|in:mt4,mt5',
        ]);

        $importer = new CsvTradeImporter();
        $result = $importer->import($request->file('csv_file'), Auth::id(), $request->input('csv_format'));

        $message = "{$result->created} operaciones importadas";
        if ($result->duplicates > 0) {
            $message .= ", {$result->duplicates} duplicadas omitidas";
        }
        if ($result->hasErrors()) {
            $message .= ", " . count($result->errors) . " errores";
        }

        return redirect()->route('journal.connections')
            ->with($result->hasErrors() ? 'warning' : 'success', $message);
    }
}
