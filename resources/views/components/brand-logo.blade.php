@props([
    'size' => 'md', // sm, md, lg
    'link' => true,
])

@php
    $iconSize = match($size) {
        'sm' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        default => 'w-10 h-10',
    };
    $titleSize = match($size) {
        'sm' => 'text-xs',
        'lg' => 'text-base md:text-lg',
        default => 'text-sm font-extrabold',
    };
    $subSize = match($size) {
        'sm' => 'text-[9px]',
        'lg' => 'text-xs',
        default => 'text-[11px]',
    };
@endphp

@if($link)
<a href="{{ route('home') }}" class="flex items-center gap-3 group">
@else
<div class="flex items-center gap-3">
@endif
    <!-- Blue Hexagonal Shield Emblem with Building Icon -->
    <div class="{{ $iconSize }} rounded-2xl bg-gradient-to-tr from-blue-600 via-blue-500 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-blue-500/25 shrink-0 group-hover:scale-105 transition-transform duration-200">
        <svg class="w-3/5 h-3/5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 3L2 10h20L12 3z"/>
        </svg>
    </div>
    <div class="text-left flex flex-col justify-center">
        <span class="{{ $titleSize }} font-extrabold text-slate-900 dark:text-white leading-tight tracking-tight">
            AI-Community
        </span>
        <span class="{{ $titleSize }} font-extrabold text-slate-900 dark:text-white leading-tight tracking-tight">
            Complaint Management System
        </span>
        <span class="{{ $subSize }} font-medium text-slate-500 dark:text-slate-400 leading-tight mt-0.5">
            North Poblacion, Maramag
        </span>
    </div>
@if($link)
</a>
@else
</div>
@endif
