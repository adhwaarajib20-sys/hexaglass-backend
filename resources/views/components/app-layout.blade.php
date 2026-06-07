@props(['title' => 'Dashboard'])

<x-layouts.app :title="$title">
    {{ $slot }}
</x-layouts.app>
