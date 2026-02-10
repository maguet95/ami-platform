<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('bitacora.show', $trade) }}" class="text-surface-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-xl font-bold text-white">Editar Trade</h2>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('bitacora.update', $trade) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('bitacora._form', ['pairs' => $pairs, 'trade' => $trade])
        </form>
    </div>
</x-app-layout>
