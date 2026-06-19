@props(['route', 'active' => false])

<a href="{{ route($route) }}"
   class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition
   {{ $active ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
    {{ $slot }}
</a>