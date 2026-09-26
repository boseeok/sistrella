@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'autocomplete' => null, 'hint' => null])
{{-- Labelled form input with old() value and inline validation feedback --}}
@php $id = 'f-'.$name; @endphp
<div>
    <label for="{{ $id }}" class="form-label">{{ $label }}@if($required)<span class="text-danger" aria-hidden="true"> *</span>@endif</label>
    @if($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'rows' => 2]) }}
            @if($required) required @endif @error($name) aria-invalid="true" aria-describedby="{{ $id }}-error" @enderror>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => 'form-control'.($errors->has($name) ? ' is-invalid' : '')]) }}
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if($required) required @endif @error($name) aria-invalid="true" aria-describedby="{{ $id }}-error" @enderror>
    @endif
    @error($name)<div id="{{ $id }}-error" class="invalid-feedback">{{ $message }}</div>@enderror
    @if($hint)<div class="form-text">{{ $hint }}</div>@endif
</div>
