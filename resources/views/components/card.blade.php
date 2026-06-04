<div {{ $attributes->merge(['class' => 'card']) }}>
    @isset($title)
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
            {{ $header ?? '' }}
        </div>
    @endisset

    {{ $slot }}
</div>
