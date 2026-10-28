<div class="bg-card rounded-lg shadow-sm border border-border p-6">
    <h3 class="text-lg font-semibold mb-4 text-foreground">اللغات</h3>
    <div class="space-y-2">
        @forelse($languages as $language)
            <div class="flex items-center justify-between">
                <span class="text-foreground">{{ $language['name'] }}</span>
                <span class="text-sm px-2 py-1 rounded
                    @if($loop->first) bg-green-100 text-green-800 @endif
                    @if($loop->iteration == 2) bg-primary text-primary-foreground @endif
                    @if($loop->last && !$loop->first && $loop->iteration != 2) bg-yellow-100 text-yellow-800 @endif">
                    {{ $language['level'] }}
                </span>
            </div>
        @empty
            <p class="text-gray-500 text-sm">لم يتم تحديد أي لغات.</p>
        @endforelse
    </div>
</div>
