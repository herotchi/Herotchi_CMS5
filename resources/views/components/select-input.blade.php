@props(['name', 'array', 'old'])

<select id="{{ $name }}" class="form-select{{ $errors->has($name) ? ' is-invalid' : '' }}"
    name="{{ $name }}">
    <option value="">---</option>
    @foreach($array as $value)
        <option value="{{ $value->id }}" @selected(old($name)==$value->id)>
            {{ $value->name }}
        </option>
    @endforeach
</select>