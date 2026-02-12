<x-layouts.app>
    <x-slot:title>Ranking</x-slot:title>

    <section class="pt-32 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-3xl lg:text-4xl font-bold text-white">Ranking de Traders</h1>
                <p class="mt-3 text-surface-400 max-w-xl mx-auto">Los traders más dedicados de la comunidad AMI.</p>
            </div>

            @if($users->isEmpty())
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 bg-surface-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-surface-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.003 6.003 0 01-2.573 1.182 6.016 6.016 0 01-5.434 0A6.002 6.002 0 015.73 9.728" />
                        </svg>
                    </div>
                    <p class="text-surface-400">Aun no hay traders en el ranking. Se el primero en activar tu perfil publico.</p>
                </div>
            @else
                {{-- Top 3 --}}
                @if($users->count() >= 3)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                        @foreach($users->take(3) as $index => $rankedUser)
                            @php
                                $borderColor = match($index) {
                                    0 => 'border-yellow-500/40',
                                    1 => 'border-gray-400/40',
                                    2 => 'border-amber-700/40',
                                    default => 'border-surface-700/50',
                                };
                                $medal = match($index) {
                                    0 => '&#x1f947;',
                                    1 => '&#x1f948;',
                                    2 => '&#x1f949;',
                                    default => '',
                                };
                                $bgGlow = match($index) {
                                    0 => 'bg-yellow-500/5',
                                    1 => 'bg-gray-400/5',
                                    2 => 'bg-amber-700/5',
                                    default => 'bg-surface-900/80',
                                };
                            @endphp
                            <div class="{{ $bgGlow }} border {{ $borderColor }} rounded-2xl p-6 text-center {{ $index === 0 ? 'sm:order-2' : ($index === 1 ? 'sm:order-1' : 'sm:order-3') }}">
                                <span class="text-3xl">{!! $medal !!}</span>
                                <div class="mt-3">
                                    @if($rankedUser->avatar)
                                        <img src="{{ asset('storage/' . $rankedUser->avatar) }}" alt="{{ $rankedUser->name }}"
                                             class="w-16 h-16 rounded-full object-cover mx-auto">
                                    @else
                                        <div class="w-16 h-16 rounded-full bg-ami-500/20 flex items-center justify-center mx-auto">
                                            <span class="text-xl font-bold text-ami-400">{{ substr($rankedUser->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('profile.public', $rankedUser) }}"
                                   class="block mt-3 text-sm font-semibold text-white hover:text-ami-400 transition-colors">
                                    {{ $rankedUser->name }}
                                </a>
                                <p class="text-xs text-surface-500">Nivel {{ $rankedUser->getLevel() }}</p>
                                <p class="text-lg font-bold text-amber-400 mt-1">{{ number_format($rankedUser->total_xp) }} XP</p>
                                @if($rankedUser->current_streak > 0)
                                    <p class="text-xs text-orange-400 mt-1">{{ $rankedUser->current_streak }}d racha</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Full List --}}
                <div class="bg-surface-900/80 border border-surface-700/50 rounded-2xl overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-surface-700/50">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-surface-500 uppercase">Trader</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-surface-500 uppercase hidden sm:table-cell">Nivel</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-surface-500 uppercase">XP</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-surface-500 uppercase hidden sm:table-cell">Racha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $rankedUser)
                                <tr class="border-b border-surface-800/50 last:border-0 {{ $currentUser && $currentUser->id === $rankedUser->id ? 'bg-ami-500/5' : '' }}">
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-semibold {{ $index < 3 ? 'text-amber-400' : 'text-surface-500' }}">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('profile.public', $rankedUser) }}" class="flex items-center gap-3 group">
                                            @if($rankedUser->avatar)
                                                <img src="{{ asset('storage/' . $rankedUser->avatar) }}" alt="{{ $rankedUser->name }}"
                                                     class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-ami-500/20 flex items-center justify-center">
                                                    <span class="text-xs font-bold text-ami-400">{{ substr($rankedUser->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium text-white group-hover:text-ami-400 transition-colors">{{ $rankedUser->name }}</span>
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-center hidden sm:table-cell">
                                        <span class="text-sm text-surface-400">{{ $rankedUser->getLevel() }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-sm font-semibold text-amber-400">{{ number_format($rankedUser->total_xp) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right hidden sm:table-cell">
                                        <span class="text-sm text-orange-400">{{ $rankedUser->current_streak > 0 ? $rankedUser->current_streak . 'd' : '-' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
