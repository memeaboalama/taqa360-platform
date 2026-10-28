<div class="bg-card rounded-lg shadow-sm border border-border p-6">
    <div class="text-center mb-6">
        <img class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" src="'https://via.placeholder.com/128' }}" alt="{{ $educator->name ?? "د. أحمد محمد علي" }}">
        <h2 class="text-xl font-bold text-foreground">{{ $educator->name ?? 'د. أحمد محمد علي' }}</h2>
        <p class="text-gray-600">{{ $educator->title ?? 'أستاذ في علوم الحاسوب' }}</p>
        <div class="flex items-center justify-center mt-2">
            <span class="text-primary">★★★★★</span>
            <span class="text-sm text-gray-600 mr-2">(4.9) · 156 تقييم</span>
        </div>
    </div>

    <div class="space-y-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-primary/20">
                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <span class="text-foreground">{{ $educator->location ?? 'الرياض، السعودية' }}</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"><svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
            <span class="text-foreground">متاح الآن</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center"><svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
            <span class="text-foreground">{{ $educator->experience ?? '15 سنة' }} خبرة</span>
        </div>
    </div>

    <div class="mt-6 space-y-3">
        <button class="w-full text-primary-foreground bg-primary py-3 px-4 rounded-lg font-semibold transition-colors">تواصل معي</button>
        <a href="{{route('educator:profile.builder')}}" class="w-full block text-center border border-border text-foreground py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition-colors">إعداد ملفك الشخصي</a>
        <button class="w-full border border-border text-foreground py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition-colors">طلب خدمة</button>
    </div>
</div>
