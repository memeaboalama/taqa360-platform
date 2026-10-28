<div class="space-y-8">
    <div>
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-semibold text-lg text-gray-800">@lang('educator::home.overview.current_requests')</h4>
            <a href="{{ request()->url() }}?tab=requests" wire:navigate class="text-sm text-primary hover:underline">@lang('educator::home.overview.all_requests')</a>
        </div>
        <div class="space-y-3">
            @forelse ($currentRequests as $request)
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h5 class="font-medium text-gray-800">{{ $request['title'] }}</h5>
                        @php
                            $statusKey = $request['status'] instanceof \BackedEnum ? $request['status']->value : $request['status'];
                            $statusClass = match($statusKey) {
                                'new' => 'badge-blue',
                                'in_progress' => 'badge-yellow',
                                'in_review' => 'badge-purple',
                                default => 'badge-gray',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">
                            @lang('educator::home.status_enums.' . $statusKey)
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">@lang('educator::home.overview.client'): {{ $request['client'] }}</p>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm text-gray-500">@lang('educator::home.overview.deadline'): {{ \Carbon\Carbon::parse($request['deadline'])->translatedFormat('d M, Y') }}</span>
                        <span class="font-semibold text-primary">{{ $request['price'] }}</span>
                    </div>
                </div>
            @empty
                <div class="border border-dashed rounded-lg p-8 text-center text-gray-500">
                    <p>@lang('educator::home.overview.no_current_requests')</p>
                </div>
            @endforelse
        </div>
    </div>

    <div>
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-semibold text-lg text-gray-800">@lang('educator::home.overview.latest_reviews')</h4>
            <a href="{{ request()->url() }}?tab=reviews" wire:navigate class="text-sm text-primary hover:underline">@lang('educator::home.overview.all_reviews')</a>
        </div>
        <div class="space-y-3">
            @forelse ($recentReviews as $review)
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-medium text-gray-800">{{ $review['client'] }}</span>
                        <div class="flex items-center gap-1 rtl:gap-2">
                            <div class="flex text-[#EA993E]">
                                @foreach(range(1,5) as $i)
                                    <svg class="w-4 h-4 {{ $i <= $review['rating'] ? 'fill-current' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                @endforeach
                            </div>
                            <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($review['date'])->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $review['comment'] }}</p>
                </div>
            @empty
                <div class="border border-dashed rounded-lg p-8 text-center text-gray-500">
                    <p>@lang('educator::home.overview.no_recent_reviews')</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
