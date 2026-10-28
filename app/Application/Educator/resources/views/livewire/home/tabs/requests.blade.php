<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h3 class="text-xl font-semibold text-gray-800">@lang('educator::home.requests.manage_requests')</h3>
        <div class="flex gap-2">
            <select class="form-input-sm">
                <option>@lang('educator::home.requests.all_requests')</option>
                <option>@lang('educator::home.requests.new_requests')</option>
                <option>@lang('educator::home.requests.in_progress_requests')</option>
                <option>@lang('educator::home.requests.completed_requests')</option>
            </select>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($requests as $request)
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex flex-col sm:flex-row justify-between items-start mb-4 gap-2">
                    <div>
                        <h4 class="font-semibold text-lg text-gray-800">{{ $request['title'] }}</h4>
                        <p class="text-gray-500">@lang('educator::home.overview.client'): {{ $request['client'] }}</p>
                    </div>
                    @php
                        $statusKey = $request['status'] instanceof \BackedEnum ? $request['status']->value : $request['status'];
                        $statusClass = match($statusKey) {
                            'new' => 'badge-blue',
                            'in_progress' => 'badge-yellow',
                            'in_review' => 'badge-purple',
                            'completed' => 'badge-green',
                            default => 'badge-gray',
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        @lang('educator::home.status_enums.' . $statusKey)
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4 text-center sm:text-start">
                    <div>
                        <span class="text-sm text-gray-500">@lang('educator::home.overview.deadline')</span>
                        <p class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($request['deadline'])->translatedFormat('d M, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">@lang('educator::home.overview.price')</span>
                        <p class="font-medium text-primary">{{ $request['price'] }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">@lang('educator::home.requests.status')</span>
                        <p class="font-medium text-gray-700">@lang('educator::home.status_enums.' . $statusKey)</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 pt-4 border-t">
                    <button class="btn-primary-sm">@lang('educator::home.requests.view_details')</button>
                    <button class="btn-secondary-sm">@lang('educator::home.requests.update_status')</button>
                    <button class="btn-secondary-sm">@lang('educator::home.requests.contact_client')</button>
                </div>
            </div>
        @empty
            <div class="border border-dashed rounded-lg p-8 text-center text-gray-500">
                <p>@lang('educator::home.requests.no_requests_to_display')</p>
            </div>
        @endforelse
    </div>
</div>
