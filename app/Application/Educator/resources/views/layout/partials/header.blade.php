<header class="bg-white/80 backdrop-blur-lg sticky top-0 z-40 shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <a href="{{ route('educator:home') }}" class="flex items-center gap-2">
                <img src="{{ asset('assets/ecommerce/images/logo.png') }}" alt="TAQA"
                    class="h-12 w-auto object-contain">
            </a>
            <nav class="hidden md:flex items-center gap-6">
                <a href="#" class="text-gray-600 hover:text-[#EA993E] transition-colors">الخدمات</a>
                <a href="#" class="text-gray-600 hover:text-[#EA993E] transition-colors">المزودون</a>
                <a href="#" class="text-gray-600 hover:text-[#EA993E] transition-colors">من نحن</a>
            </nav>
            <div class="flex items-center gap-4">
                <span class="font-semibold">{{ auth('educator')->user()->name }}</span>
                <form method="POST" action="{{ route('educator:logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-[#306FAA] text-white rounded-lg hover:bg-[#245C8E] transition-colors text-sm">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
