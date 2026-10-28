<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold" style="color: #333333;">@lang('educator::home.ratings_and_reviews')</h3>
        <div class="text-sm text-gray-600">
            @lang('educator::home.average_rating'): <span class="font-semibold" style="color: #EA993E;">{{ number_format((float) $educator->rating, 1) }}/5</span>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="font-medium" style="color: #333333;">{{ mb_substr($review['client'], 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="font-medium" style="color: #333333;">{{ $review['client'] }}</h4>
                            <div class="flex items-center gap-2">
                                <span style="color: #EA993E;">{{ str_repeat('★', $review['rating']) }}<span class="text-gray-300">{{ str_repeat('★', 5 - $review['rating']) }}</span></span>
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($review['date'])->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">{{ $review['comment'] }}</p>
                <div class="mt-3 flex gap-4 rtl:gap-4">
                    <button class="text-sm hover:underline" style="color: #306FAA;">@lang('educator::home.reply_to_review')</button>
                    <button class="text-sm text-gray-600 hover:underline">@lang('educator::home.report')</button>
                </div>
            </div>
        @empty
            <div class="border border-dashed rounded-lg p-8 text-center text-gray-500">
                <p>@lang('educator::home.reviews.no_reviews_yet')</p>
            </div>
        @endforelse
    </div>
</div>
