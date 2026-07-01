@props(['name', 'label', 'options', 'selected' => null, 'required' => false, 'placeholder' => 'Seleccionar...'])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'block w-full rounded-md shadow-sm text-sm px-3 py-2 border ' . 
            ($errors->has($name) 
                ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500' 
                : 'border-slate-300 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500')
        ]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $selected) === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>