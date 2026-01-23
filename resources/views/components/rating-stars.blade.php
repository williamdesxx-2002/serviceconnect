@props(['rating', 'size' => 'sm'])

@php
    $rating = round($rating);
    $sizeClasses = [
        'sm' => 'fs-6',
        'md' => 'fs-5', 
        'lg' => 'fs-4'
    ];
    $sizeClass = $sizeClasses[$size] ?? 'fs-6';
@endphp

<div class="{{ $sizeClass }}">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= $rating)
            <i class="fas fa-star text-warning"></i>
        @else
            <i class="far fa-star text-warning"></i>
        @endif
    @endfor
</div>
