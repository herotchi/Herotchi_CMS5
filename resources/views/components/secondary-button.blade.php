@props(['href' => route('admin.top')])
{{--<button {{ $attributes->merge(['type' => 'button', 'class' => '']) }}>
    {{ $slot }}
</button>--}}
<a class="btn btn-secondary" href="{{ $href }}" role="button">
    {{ $slot }}
</a>
