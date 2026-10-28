<div class="bg-card rounded-lg shadow-sm border border-border p-6">
    <h3 class="text-xl font-semibold mb-4 text-foreground">التقييمات</h3>
    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class=" @if(!$loop->last) border-b border-border pb-4 @endif ">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ $review['user_avatar'] }}" alt="{{ $review['user_name'] }}">
                        <span class="font-medium text-foreground">{{ $review['user_name'] }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="text-primary">
                            @for ($i = 0; $i < $review['rating']; $i++)★@endfor @for($i = 0; $i < 5 - $review['rating']; $i++)<span class="text-gray-300">★</span>@endfor
                        </span>
                        <span class="text-sm text-gray-500">{{ $review['date'] }}</span>
                    </div>
                </div>
                <p class="text-foreground">
                    {{ $review['comment'] }}
                </p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">لا توجد تقييمات لعرضها حاليًا.</p>
        @endforelse
    </div>

    @if(count($reviews) > 0)
        <button class="mt-4 font-medium text-primary">
            عرض جميع التقييمات
        </button>
    @endif
</div>
