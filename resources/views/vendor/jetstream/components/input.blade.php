@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-primary focus:ring-1 focus:ring-primary focus:ring-opacity-50 rounded-md shadow-sm']) !!}>
