@props(['name', 'array'])

<div class="btn-group mt-0">
    @foreach($array as $key => $value)
        <input type="radio" class="btn-check" name="{{ $name }}" id="{{ $name }}_{{ $key }}"
            value="{{ $key }}" @checked(old($name)==$key) required>
        <label class="btn btn-outline-success form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
            for="{{ $name }}_{{ $key }}">{{ $value }}</label>
    @endforeach
</div>
<div class="mt-0{{ $errors->has($name) ? ' is-invalid' : '' }}"></div>