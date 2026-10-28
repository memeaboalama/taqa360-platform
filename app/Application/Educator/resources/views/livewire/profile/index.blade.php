<div class="min-h-screen bg-background">
    <section class="py-20 bg-primary">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-6 text-primary-foreground">لوحة التحكم الرئيسية</h1>
                <p class="text-xl opacity-90 text-primary-foreground">
                    أدرِ ملفك الشخصي وخدماتك وتقييماتك من هنا.
                </p>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 space-y-6">
                <livewire:educator::profile.sidebar :educator-id="$educatorId" :key="'sidebar-' . $educatorId" />
                <livewire:educator::profile.languages :educator-id="$educatorId" :key="'languages-' . $educatorId" />
            </div>

            <div class="lg:col-span-2 space-y-6">
                <livewire:educator::profile.about :educator-id="$educatorId" :key="'about-' . $educatorId" />
                <livewire:educator::profile.services :educator-id="$educatorId" :key="'services-' . $educatorId" />
                <livewire:educator::profile.portfolio :educator-id="$educatorId" :key="'portfolio-' . $educatorId" />
                <livewire:educator::profile.reviews :educator-id="$educatorId" :key="'reviews-' . $educatorId" />
            </div>
        </div>
    </div>
</div>
