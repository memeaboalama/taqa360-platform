@if(isset($subCategories) && $subCategories->count() > 0)
<div class="subcategories-section" up-hungry>
    <div class="subcategories-container">
        <h3 class="subcategories-title">@lang('ecommerce::ecommerce.categories.sub_categories')</h3>
        <div class="subcategories-grid">
            @foreach($subCategories as $subCategory)
            <a up-follow up-target=".collection-content"
               href="{{ route('categories.filter', array_merge(request()->query(), ['sub_category' => $subCategory->id])) }}"
               class="subcategory-card">
                <div class="subcategory-image">
                    @if($subCategory->image)
                        <img src="{{ $subCategory->image }}" alt="{{ $subCategory->title }}">
                    @else
                        <img src="{{ asset('assets/ecommerce/images/placeholder-category.jpg') }}" alt="{{ $subCategory->title }}">
                    @endif
                </div>
                <div class="subcategory-name">{{ Str::limit($subCategory->title, 25) }}</div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
