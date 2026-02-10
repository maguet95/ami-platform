<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManualTradeRequest;
use App\Models\ManualTrade;
use App\Models\ManualTradeImage;
use App\Models\TradePair;
use App\Services\ManualJournalMetricsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManualJournalController extends Controller
{
    public function __construct(
        private ManualJournalMetricsService $metricsService,
    ) {}

    public function index(Request $request)
    {
        if (! config('journal.manual_enabled')) {
            abort(404);
        }

        $user = Auth::user();
        $metrics = $this->metricsService->getMetrics($user->id);

        $query = ManualTrade::forUser($user->id)
            ->with('tradePair', 'images')
            ->orderByDesc('trade_date');

        // Filters
        if ($request->filled('pair')) {
            $query->where('trade_pair_id', $request->pair);
        }

        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('result')) {
            match ($request->result) {
                'winning' => $query->winning(),
                'losing' => $query->losing(),
                'breakeven' => $query->breakeven(),
                default => null,
            };
        }

        if ($request->filled('from')) {
            $query->where('trade_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->where('trade_date', '<=', $request->to);
        }

        if ($request->filled('emotion')) {
            $query->where('emotion_before', $request->emotion);
        }

        if ($request->filled('rating')) {
            $query->where('overall_rating', $request->rating);
        }

        // Sort
        $sortField = match ($request->get('sort')) {
            'pnl' => 'pnl',
            'rating' => 'overall_rating',
            default => 'trade_date',
        };
        $sortDir = $request->get('order', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDir);

        $trades = $query->paginate(12)->withQueryString();

        // Pairs used by user (for filter dropdown)
        $userPairs = ManualTrade::forUser($user->id)
            ->select('trade_pair_id')
            ->distinct()
            ->with('tradePair:id,symbol,display_name')
            ->get()
            ->pluck('tradePair')
            ->filter()
            ->unique('id')
            ->sortBy('symbol')
            ->values();

        return view('bitacora.index', compact('metrics', 'trades', 'userPairs'));
    }

    public function create()
    {
        if (! config('journal.manual_enabled')) {
            abort(404);
        }

        $pairs = TradePair::active()->orderBy('market')->orderBy('symbol')->get();

        return view('bitacora.create', compact('pairs'));
    }

    public function store(ManualTradeRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // Remove image data from trade fields
        $images = $data['images'] ?? [];
        $captions = $data['captions'] ?? [];
        unset($data['images'], $data['captions']);

        $data['user_id'] = $user->id;
        $data['had_plan'] = $request->boolean('had_plan');
        $data['would_take_again'] = $request->has('would_take_again') ? $request->boolean('would_take_again') : null;
        $data['mistakes'] = $request->input('mistakes', []);

        $trade = ManualTrade::create($data);

        // Handle images
        $this->storeImages($trade, $images, $captions);

        $this->metricsService->invalidateCache($user->id);

        return redirect()->route('bitacora.show', $trade)
            ->with('success', 'Trade registrado exitosamente.');
    }

    public function show(ManualTrade $trade)
    {
        $this->authorizeOwnership($trade);

        $trade->load('tradePair', 'images');

        return view('bitacora.show', compact('trade'));
    }

    public function edit(ManualTrade $trade)
    {
        $this->authorizeOwnership($trade);

        if (! config('journal.manual_enabled')) {
            abort(404);
        }

        $trade->load('images');
        $pairs = TradePair::active()->orderBy('market')->orderBy('symbol')->get();

        return view('bitacora.edit', compact('trade', 'pairs'));
    }

    public function update(ManualTradeRequest $request, ManualTrade $trade)
    {
        $this->authorizeOwnership($trade);

        $data = $request->validated();

        $images = $data['images'] ?? [];
        $captions = $data['captions'] ?? [];
        unset($data['images'], $data['captions']);

        $data['had_plan'] = $request->boolean('had_plan');
        $data['would_take_again'] = $request->has('would_take_again') ? $request->boolean('would_take_again') : null;
        $data['mistakes'] = $request->input('mistakes', []);

        $trade->update($data);

        // Handle new images
        $this->storeImages($trade, $images, $captions);

        $this->metricsService->invalidateCache(Auth::id());

        return redirect()->route('bitacora.show', $trade)
            ->with('success', 'Trade actualizado exitosamente.');
    }

    public function destroy(ManualTrade $trade)
    {
        $this->authorizeOwnership($trade);

        // Delete image files
        foreach ($trade->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $trade->delete();

        $this->metricsService->invalidateCache(Auth::id());

        return redirect()->route('bitacora.index')
            ->with('success', 'Trade eliminado exitosamente.');
    }

    public function duplicate(ManualTrade $trade)
    {
        $this->authorizeOwnership($trade);

        $newTrade = $trade->replicate(['id', 'created_at', 'updated_at', 'deleted_at']);
        $newTrade->trade_date = now()->toDateString();
        $newTrade->save();

        $this->metricsService->invalidateCache(Auth::id());

        return redirect()->route('bitacora.edit', $newTrade)
            ->with('success', 'Trade duplicado. Edita los detalles y guarda.');
    }

    public function destroyImage(ManualTradeImage $image)
    {
        $trade = $image->manualTrade;
        $this->authorizeOwnership($trade);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Imagen eliminada.');
    }

    // Private helpers

    private function authorizeOwnership(ManualTrade $trade): void
    {
        if ($trade->user_id !== Auth::id()) {
            abort(404);
        }
    }

    private function storeImages(ManualTrade $trade, array $images, array $captions): void
    {
        foreach ($images as $index => $file) {
            $path = $file->store('bitacora/' . $trade->user_id, 'public');

            $trade->images()->create([
                'image_path' => $path,
                'caption' => $captions[$index] ?? null,
                'sort_order' => $trade->images()->count(),
            ]);
        }
    }
}
