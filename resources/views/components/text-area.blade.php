@props(['name', 'old', 'row' => 4])

<textarea id="{{ $name }}" class="shadow-sm form-control{{ $errors->has($name) ? ' is-invalid' : '' }}" 
    name="{{ $name }}" rows="{{ $row }}">{{ $old }}</textarea>