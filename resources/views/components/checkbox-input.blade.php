@props(['name', 'array', 'input' => array()])

<div class="btn-group mt-0 shadow-sm">
    @foreach($array as $key => $value)
    <input type="checkbox" class="btn-check" name="{{ $name }}[]" id="{{ $name }}_{{ $key }}"
        value="{{ $key }}" autocomplete="off" @checked(in_array($key, old($name, $input)))>
    <label class="btn btn-outline-success form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
        for="{{ $name }}_{{ $key }}">{{ $value }}</label>
    @endforeach
</div>
<div class="mt-0{{ $errors->has($name) ? ' is-invalid' : '' }}"></div>
<div class="mt-0{{ $errors->has($name . '.*') ? ' is-invalid' : '' }}"></div>