<x-layouts.app>
    <x-slot:title>Planes</x-slot:title>

    {{-- Hero --}}
    <section class="pt-32 pb-16 relative">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-ami-400 text-sm font-semibold uppercase tracking-wider">Membresías</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-bold text-white leading-tight">
                Elige tu <span class="text-ami-400">Plan</span>
            </h1>
            <p class="mt-6 text-lg text-surface-300 leading-relaxed max-w-2xl mx-auto">
                Accede a todos los cursos premium y acelera tu formación como trader profesional.
            </p>
        </div>
    </section>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-bearish/10 border border-bearish/20 text-bearish rounded-xl p-4 text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Plans Grid --}}
    <section class="pb-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($plans as $plan)
                <div class="relative bg-surface-800/40 border {{ $plan->is_featured ? 'border-ami-500/50 ring-1 ring-ami-500/20' : 'border-surface-700/50' }} rounded-2xl p-8 hover:border-ami-500/30 transition-all duration-300">
                    {{-- Featured Badge --}}
                    @if($plan->is_featured)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                            <span class="px-4 py-1 text-xs font-semibold text-white bg-ami-500 rounded-full shadow-lg shadow-ami-500/25">
                                Más Popular
                            </span>
                        </div>
                    @endif

                    {{-- Plan Header --}}
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-white">{{ $plan->name }}</h3>
                        <p class="mt-2 text-sm text-surface-400">{{ $plan->description }}</p>
                    </div>

                    {{-- Price --}}
                    <div class="text-center mb-8">
                        <span class="text-4xl font-bold text-white">{{ $plan->getFormattedPrice() }}</span>
                        <span class="text-surface-400 text-sm">/{{ $plan->interval === 'monthly' ? 'mes' : 'año' }}</span>
                    </div>

                    {{-- Features --}}
                    <ul class="space-y-3 mb-8">
                        @foreach($plan->features ?? [] as $feature)
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-bullish flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span class="text-sm text-surface-300">{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>

                    {{-- CTA --}}
                    <div class="text-center">
                        @auth
                            @if(Auth::user()->hasActiveSubscription())
                                <span class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-bullish bg-bullish/10 border border-bullish/20 rounded-xl">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    Ya estás suscrito
                                </span>
                            @else
                                <form action="{{ route('subscription.checkout', $plan) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full px-6 py-3 text-sm font-semibold text-white {{ $plan->is_featured ? 'bg-ami-500 hover:bg-ami-600 shadow-lg shadow-ami-500/25' : 'bg-surface-700 hover:bg-surface-600' }} rounded-xl transition-all duration-200">
                                        Suscribirme
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('register') }}"
                               class="inline-block w-full px-6 py-3 text-sm font-semibold text-white text-center {{ $plan->is_featured ? 'bg-ami-500 hover:bg-ami-600 shadow-lg shadow-ami-500/25' : 'bg-surface-700 hover:bg-surface-600' }} rounded-xl transition-all duration-200">
                                Crear cuenta para suscribirse
                            </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            {{-- FAQ Note --}}
            <div class="mt-12 text-center">
                <p class="text-surface-500 text-sm">
                    Cancela cuando quieras. Sin compromisos.
                    <a href="{{ route('contact') }}" class="text-ami-400 hover:text-ami-300">¿Tienes preguntas?</a>
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
