<div class="min-h-screen bg-slate-50">
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold text-gray-800">@lang('educator::home.dashboard_title')</h1>
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-primary transition-colors">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">{{ $unreadCount }}</span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition style="display: none;"
                             class="absolute end-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                                <h6 class="font-bold text-gray-800">@lang('educator::home.notifications')</h6>
                                @if($unreadCount > 0)
                                    <button wire:click="markNotificationsRead" class="text-xs text-primary hover:underline">@lang('educator::home.mark_all_read')</button>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($notifications as $notification)
                                    <button wire:click="openNotification({{ $notification->id }})" class="w-full text-start px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ $notification->is_read ? '' : 'bg-primary/10' }}">
                                        <p class="text-sm font-semibold text-gray-800">{{ $notification->title }}</p>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $notification->body }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </button>
                                @empty
                                    <p class="text-sm text-gray-500 text-center py-6">@lang('educator::home.no_notifications')</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="relative" x-data="{ langOpen: false }" wire:ignore>
                        <button type="button" @click="langOpen = !langOpen" class="flex items-center gap-1 p-2 text-gray-500 hover:text-gray-800 transition-colors focus:outline-none">
                            <img src="{{ asset('assets/vendor') }}/images/flags/{{ LaravelLocalization::getCurrentLocale() }}.jpg" alt="lang" class="w-6 h-6 rounded-full object-cover">
                        </button>
                        <div x-show="langOpen" @click.away="langOpen = false" x-transition style="display: none;"
                             class="absolute end-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <a hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors {{ $localeCode === LaravelLocalization::getCurrentLocale() ? 'font-bold' : '' }}">
                                    <img src="{{ asset('assets/vendor') }}/images/flags/{{ $localeCode }}.jpg" alt="{{ $localeCode }}" class="w-5 h-5 rounded-full object-cover">
                                    {{ $properties['native'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                            <span class="avatar avatar-md w-10 h-10 rounded-full overflow-hidden border border-gray-200 bg-white inline-flex items-center justify-center">
                                <img src="{{ asset('assets/ecommerce/images/logo.png') }}" class="w-full h-full object-cover" alt="avatar">
                            </span>
                            <span class="hidden sm:inline text-sm font-semibold text-gray-800">{{ $educator->name }}</span>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition style="display: none;"
                             class="absolute end-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <form method="POST" action="{{ route('educator:logout') }}" onsubmit="document.getElementById('logout-loader').style.display='flex'">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ri-logout-box-r-line' : 'ri-logout-box-line' }} text-lg leading-none"></i>
                                    @lang('educator::auth.logout')
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @if($selectedNotification)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" wire:click="closeNotification">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[80vh] overflow-y-auto" wire:click.stop>
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
                    <h5 class="font-bold text-gray-800">{{ $selectedNotification->title }}</h5>
                    <button wire:click="closeNotification" class="text-gray-500 hover:text-gray-800 text-2xl leading-none" aria-label="close">&times;</button>
                </div>
                <div class="px-5 py-4 text-gray-700 whitespace-pre-line">{{ $selectedNotification->body }}</div>
                <div class="px-5 py-3 border-t border-gray-200 text-xs text-gray-400">{{ $selectedNotification->created_at->diffForHumans() }}</div>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($stats as $stat)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">{{ $stat['title'] }}</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $stat['value'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white" style="background-color: {{ $stat['color'] }};">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" /></svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <aside class="lg:col-span-1 space-y-6">
                <livewire:educator::home.components.sidebar :educator="$educator" />
                <livewire:educator::home.components.quickstats :educator="$educator" />
            </aside>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="border-b border-gray-200">
                        <nav class="flex overflow-x-auto -mb-px">
                            @php
                                $tabs = [
                                    'overview' => __('educator::home.tabs.overview'),
                                    'requests' => __('educator::home.tabs.requests'),
                                    'reviews' => __('educator::home.tabs.reviews'),
                                    'services' => __('educator::home.tabs.services'),
                                    'profile' => __('educator::home.tabs.profile'),
                                ];
                            @endphp
                            @foreach($tabs as $tabId => $tabLabel)
                                <button wire:click="changeTab('{{ $tabId }}')" class="px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $activeTab === $tabId ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300' }}">
                                    {{ $tabLabel }}
                                </button>
                            @endforeach
                        </nav>
                    </div>
                    <div class="p-6">
                        @if ($activeTab === 'overview')
                            <livewire:educator::home.tabs.overview :educator="$educator" />
                        @elseif ($activeTab === 'requests')
                            <livewire:educator::home.tabs.requests :educator="$educator" />
                        @elseif ($activeTab === 'reviews')
                            <livewire:educator::home.tabs.reviews :educator="$educator" />
                        @elseif ($activeTab === 'services')
                            <livewire:educator::home.tabs.services :educator="$educator" />
                        @elseif ($activeTab === 'profile')
                            <livewire:educator::home.tabs.profile :educator="$educator" />
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="logout-loader" style="display: none; position: fixed; inset: 0; z-index: 999999; background: #ffffff; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <span style="width: 80px; height: 80px; background: rgba(var(--primary-rgb), 0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
            <i class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ri-logout-box-r-line' : 'ri-logout-box-line' }}" style="font-size: 32px; line-height: 1; color: rgb(var(--primary-rgb));"></i>
        </span>
        <p class="font-bold mb-3" style="color: #000;">{{ __('educator::auth.logging_out_msg') }}</p>
        <div class="w-8 h-8 border-4 border-gray-200 border-t-transparent rounded-full animate-spin" style="border-top-color: rgb(var(--primary-rgb));"></div>
    </div>
</div>
