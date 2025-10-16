@props(['colorClasses', 'role'])

<span {{ $attributes->merge(['class' => 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $colorClasses]) }}>
    {{ ucfirst($role) }}
</span>
