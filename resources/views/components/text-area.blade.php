@props(['name', 'row' => 4])

<textarea id="{{ $name }}" class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}" 
    name="{{ $name }}" rows="{{ $row }}">
    {{ old($name) }}
</textarea>