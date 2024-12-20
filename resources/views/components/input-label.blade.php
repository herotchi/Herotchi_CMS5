@props(['value', 'required'])

<label {{ $attributes->merge(['class' => 'form-label']) }}>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="text-danger fw-bold">※</span>    
    @endif
</label>