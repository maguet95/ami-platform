@props([
    'type' => 'line',
    'data' => '{}',
    'options' => '{}',
    'class' => '',
    'height' => null,
])

<div x-data="{
    chart: null,
    init() {
        this.chart = new Chart(this.$refs.canvas, {
            type: '{{ $type }}',
            data: {{ $data instanceof \Illuminate\Support\HtmlString ? $data : $data }},
            options: {{ $options instanceof \Illuminate\Support\HtmlString ? $options : $options }},
        });
    },
    destroy() {
        if (this.chart) this.chart.destroy();
    }
}" x-on:destroy="destroy()" {{ $attributes->merge(['class' => $class]) }}>
    <canvas x-ref="canvas" @if($height) height="{{ $height }}" @endif></canvas>
</div>
