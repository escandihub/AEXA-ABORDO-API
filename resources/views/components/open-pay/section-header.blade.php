@props(['icon' => 'user', 'title' => '', 'subtitle' => ''])

@php
$paths = [
    'user'        => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    'phone'       => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z',
    'credit-card' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
];
@endphp

<div class="flex items-start gap-3 pb-1">
    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $paths[$icon] ?? $paths['user'] }}"/>
        </svg>
    </div>
    <div>
        <h3 class="font-bold text-slate-800 text-base leading-tight">{{ $title }}</h3>
        @if($subtitle)
        <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>
</div>