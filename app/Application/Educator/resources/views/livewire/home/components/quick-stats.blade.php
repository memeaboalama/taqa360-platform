<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">@lang('educator::home.quick_stats.title')</h3>
    <div class="space-y-4 text-sm">
        <div class="flex justify-between items-center">
            <span class="text-gray-500">@lang('educator::home.quick_stats.earnings_this_month')</span>
            <span class="font-semibold text-green-600">{{ number_format($earningsThisMonth) }} {{ __('educator::home.currency_sar') }}</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500">@lang('educator::home.quick_stats.response_rate')</span>
            <span class="font-semibold text-gray-700">{{ $responseRate }}%</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500">@lang('educator::home.quick_stats.order_completion_rate')</span>
            <span class="font-semibold text-gray-700">{{ $completionRate }}%</span>
        </div>
    </div>
</div>
