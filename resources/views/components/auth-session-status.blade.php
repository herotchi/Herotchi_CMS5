@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-success fw-medium']) }}>
        {{ $status }}
    </div>
@endif
