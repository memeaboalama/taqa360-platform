<div class="bg-card rounded-lg shadow-sm border border-border p-6">
    <h3 class="text-xl font-semibold mb-4 text-foreground">الخدمات المقدمة</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($services as $service)
            <div class="border border-border rounded-lg p-4">
                <h4 class="font-semibold mb-2 text-foreground">{{ $service['title'] }}</h4>
                <p class="text-sm text-gray-600 mb-3">{{ $service['description'] }}</p>
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-primary">{{ $service['price'] }}</span>
                    <button class="text-sm px-3 py-1 rounded bg-primary text-primary-foreground">اطلب الآن</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
