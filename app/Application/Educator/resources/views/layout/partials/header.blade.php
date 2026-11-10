<header class="bg-white/80 backdrop-blur-lg sticky top-0 z-40 shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <a href="{{ route('educator:home') }}" class="flex items-center gap-2">
                <img src="{{ asset($platformBrand['logo']) }}" alt="{{ $platformBrand['name'][app()->getLocale()] ?? $platformBrand['name']['en'] }}"
                    class="h-12 w-auto object-contain">
            </a>
            <nav class="hidden md:flex items-center gap-6">
                <a href="#" class="text-gray-600 brand-accent-hover transition-colors">الخدمات</a>
                <a href="#" class="text-gray-600 brand-accent-hover transition-colors">المزودون</a>
                <a href="#" class="text-gray-600 brand-accent-hover transition-colors">من نحن</a>
            </nav>
            <div class="flex items-center gap-4">
                <span class="font-semibold">{{ auth('educator')->user()->name }}</span>
                <form method="POST" action="{{ route('educator:logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 brand-primary-button text-white rounded-lg transition-colors text-sm">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
