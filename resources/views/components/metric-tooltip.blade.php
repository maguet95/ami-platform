@props(['title', 'formula' => null, 'body'])

<div x-data="{
        show: false,
        style: '',
        _et: null,
        _lt: null,
        pos(btn) {
            const r = btn.getBoundingClientRect();
            const w = 264;
            const left = Math.max(8, Math.min(window.innerWidth - w - 8, r.left + r.width / 2 - w / 2));
            const bottom = window.innerHeight - r.top + 10;
            this.style = 'position:fixed;z-index:9999;bottom:' + bottom + 'px;left:' + left + 'px;width:' + w + 'px';
        },
        enter(btn) {
            clearTimeout(this._lt);
            this._et = setTimeout(() => { this.pos(btn); this.show = true; }, 350);
        },
        leave() {
            clearTimeout(this._et);
            this._lt = setTimeout(() => this.show = false, 120);
        },
        click(btn) {
            clearTimeout(this._et); clearTimeout(this._lt);
            if (this.show) { this.show = false; } else { this.pos(btn); this.show = true; }
        }
     }"
     class="inline-flex items-center">

    <button type="button"
            @mouseenter="enter($el)"
            @mouseleave="leave()"
            @click.stop="click($el)"
            @keydown.escape.window="show = false"
            class="text-surface-700 hover:text-surface-400 transition-colors focus:outline-none"
            aria-label="Info sobre {{ $title }}">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
        </svg>
    </button>

    <template x-teleport="body">
        <div x-show="show"
             x-cloak
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             :style="style"
             class="bg-surface-800 border border-surface-600/70 rounded-xl shadow-2xl p-4 text-left">

            <p class="text-xs font-semibold text-white mb-2">{{ $title }}</p>

            @if($formula)
            <div class="bg-surface-900/80 rounded-lg px-2.5 py-1.5 mb-2.5 font-mono text-[10px] text-ami-400 tracking-wide border border-surface-700/50">
                {{ $formula }}
            </div>
            @endif

            <p class="text-[11px] text-surface-300 leading-relaxed">{{ $body }}</p>
        </div>
    </template>
</div>
