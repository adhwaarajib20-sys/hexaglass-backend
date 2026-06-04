<div {{ $attributes->merge(['class' => 'text-center py-8']) }}>
    <div class="text-gray-400 mb-2">{{ $message ?? 'Tidak ada data' }}</div>
    @if(isset($action))
        <div class="mt-2">{{ $action }}</div>
    @endif
</div>
