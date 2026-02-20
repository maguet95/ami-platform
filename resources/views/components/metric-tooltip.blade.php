@props(['title', 'formula' => null, 'body'])

<div x-data="{ show: false, tooltipStyle: '' }" class="inline-flex items-center">
    <button type="button"
            @click.stop="
                const rect = $el.getBoundingClientRect();
                const w = 260;
                const left = Math.max(8, Math.min(window.innerWidth - w - 8, rect.left + rect.width / 2 - w / 2));
                const bottom = window.innerHeight - rect.top + 10;
                tooltipStyle = 'position:fixed;z-index:9999;bottom:' + bottom + 'px;left:' + left + 'px;width:' + w + 'px';
                show = !show;
            "
            @keydown.escape.window="show = false"
            class="text-surface-600 hover:text-surface-400 transition-colors ml-1 focus:outline-none"
            aria-label="Info sobre {{ $title }}">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
        </svg>
    </button>

    <template x-teleport="body">
        <div x-show="show"
             @click.away="show = false"
             x-cloak
             :style="tooltipStyle"
             class="bg-surface-800 border border-surface-600/80 rounded-xl shadow-2xl p-3.5 text-left pointer-events-auto">

            <p class="text-xs font-semibold text-white mb-1.5">{{ $title }}</p>

            @if($formula)
            <div class="bg-surface-900 rounded-lg px-2.5 py-1.5 mb-2 font-mono text-[10px] text-ami-400 tracking-wide">
                {{ $formula }}
            </div>
            @endif

            <p class="text-[11px] text-surface-300 leading-relaxed">{{ $body }}</p>
        </div>
    </template>
</div>
