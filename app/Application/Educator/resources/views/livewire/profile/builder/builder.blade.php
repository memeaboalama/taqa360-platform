<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b sticky top-0 z-20">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">@lang('educator::builder.title')</h1>
                    <p class="text-gray-600">@lang('educator::builder.subtitle')</p>
                </div>
                <div class="text-right rtl:text-left">
                    <div class="text-sm text-gray-600 mb-1">@lang('educator::builder.completion_rate')</div>
                    @php
                        $progressPercentage = count($steps) > 0 ? (count($completedSteps) / count($steps)) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <span class="font-medium text-blue-600">{{ round($progressPercentage) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-24">
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach ($steps as $id => $step)
                                @php
                                    $isCompleted = in_array($id, $completedSteps);
                                    $isCurrent = $currentStep === $id;
                                    $isAccessible = $isCompleted || $isCurrent || ($id === ($currentStep));
                                @endphp
                                <div wire:click="changeStep({{ $id }})"
                                    class="flex items-center gap-3 p-3 rounded-lg transition-all
                                        {{ $isAccessible ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' }}
                                        {{ $isCurrent ? 'bg-blue-50 border border-blue-200' : '' }}
                                        {{ $isCompleted && !$isCurrent ? 'bg-green-50 hover:bg-green-100' : '' }}
                                        {{ !$isCurrent && !$isCompleted && $isAccessible ? 'hover:bg-gray-50' : '' }}">

                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                        {{ $isCompleted && !$isCurrent ? 'bg-green-500 text-white' : '' }}
                                        {{ $isCurrent ? 'bg-blue-600 text-white' : '' }}
                                        {{ !$isCompleted && !$isCurrent ? 'bg-gray-200 text-gray-500' : '' }}">

                                        @if($isCompleted && !$isCurrent)
                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        @else
                                            @switch($id)
                                                @case(1)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                                    @break
                                                @case(2)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0l15.482 0m-15.482 0L12 10.5M12 10.5l1.5-1.5m-1.5 1.5l-1.5-1.5m1.5 1.5V5.625" /></svg>
                                                    @break
                                                @case(3)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502" /></svg>
                                                    @break
                                                @case(4)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                                    @break
                                                @case(5)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.07a2.25 2.25 0 01-2.25 2.25h-13.5a2.25 2.25 0 01-2.25-2.25v-4.07m16.5-8.25a2.25 2.25 0 00-2.25-2.25h-13.5a2.25 2.25 0 00-2.25 2.25m16.5 0v4.07m-16.5-4.07v4.07m16.5 0v3.75m-16.5-3.75v3.75m16.5 0a2.25 2.25 0 01-2.25 2.25h-13.5a2.25 2.25 0 01-2.25-2.25m16.5 0m-16.5 0" /></svg>
                                                    @break
                                                @case(6)
                                                     <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                                    @break
                                                @case(7)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.325 3.498.097 6.484-.673 8.98-2.54 1.135-.845 2.268-1.928 2.268-3.227 0-1.299-.938-2.381-2.128-3.138C15.908 9.59 13.556 8.5 10.5 8.5c-3.056 0-5.408 1.09-7.128 1.862-1.19.757-2.128 1.839-2.128 3.138z" /></svg>
                                                    @break
                                                @case(8)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                                    @break
                                                @case(9)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z" /></svg>
                                                    @break
                                            @endswitch
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium truncate {{ $isCurrent ? 'text-blue-600' : ($isCompleted && !$isCurrent ? 'text-green-700' : 'text-gray-900') }}">
                                            {{ $step['name'] }}
                                        </div>
                                        @if($isCurrent)
                                            <div class="text-xs text-gray-500">@lang('educator::builder.step_of', ['step' => $id, 'total' => count($steps)])</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 min-h-[600px]">
                    <div class="p-6 md:p-8">
                        @livewire($steps[$currentStep]['component'], ['educator' => $educator], key('step-'.$currentStep))
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if ($showConfirmationModal)
        <div
            x-data="{ show: @entangle('showConfirmationModal') }"
            x-show="show"
            x-cloak
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-60"
            @keydown.escape.window="show = false"
        >
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.away="show = false"
                class="bg-white rounded-lg shadow-xl w-full max-w-md"
            >

                <div class="p-6 text-center">
                    <div class="mx-auto flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3">
                        <h3 class="text-lg font-bold text-foreground">{{ $confirmationTitle }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ $confirmationContent }}</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-center sm:gap-3 rounded-b-lg">
                    <button
                        wire:click="executeConfirmedDelete"
                        type="button"
                        class="btn-danger w-full sm:w-auto">
                        @lang('educator::builder.yes_delete')
                    </button>
                    <button @click="show = false" type="button" class="btn-secondary w-full sm:w-auto mt-2 sm:mt-0">
                        @lang('educator::builder.cancel')
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
