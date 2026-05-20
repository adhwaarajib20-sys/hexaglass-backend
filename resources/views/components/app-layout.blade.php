@props(['title' => 'Dashboard'])

@php
    // Inject title ke layout
@endphp

<x-layouts.app :title="$title">
    {{ $slot }}
</x-layouts.app>