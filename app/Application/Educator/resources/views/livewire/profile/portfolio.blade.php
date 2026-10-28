<div class="bg-card rounded-lg shadow-sm border border-border p-6">
    <h3 class="text-xl font-semibold mb-4 text-foreground">الأعمال والإنجازات</h3>
    <div class="space-y-4">
        @forelse($portfolioItems as $item)
            <div class="border-l-4 pl-4" style="border-color: {{ $item['color'] }};">
                <h4 class="font-semibold text-foreground">{{ $item['title'] }}</h4>
                <p class="text-sm text-gray-600 mb-2">{{ $item['meta'] }}</p>
                <p class="text-foreground">{{ $item['description'] }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">لا توجد أعمال لعرضها حاليًا.</p>
        @endforelse
    </div>
</div>
