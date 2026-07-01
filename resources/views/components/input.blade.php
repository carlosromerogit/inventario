@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>
    
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        @disabled($attributes->get('disabled'))
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'block w-full rounded-md shadow-sm text-sm px-3 py-2 border ' . 
            ($errors->has($name) 
                ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' 
                : 'border-slate-300 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500')
        ]) }}
    >
    
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>