{{-- @if($childCategories->count() > 0)
<div class="collection-collapse-block open">
    <h3 class="collapse-block-title">@lang('ecommerce::ecommerce.categories.child_categories')</h3>
    <div class="collection-collapse-block-content">
        <div class="collection-brand-filter">
            <ul class="category-list">
                <li>
                    <a data-target-radio="child_category_all" class="{{ !request()->filled('child_category') || request('child_category') == 'all' ? 'active' : '' }}">
                        <span class="category-name">@lang('ecommerce::ecommerce.categories.all_childcategories')</span>
                    </a>
                </li>
                @foreach($childCategories as $childCategory)
                <li>
                    <a data-target-radio="child_category_{{ $childCategory->id }}" class="{{ request('child_category') == $childCategory->id ? 'active' : '' }}">
                        <span class="category-name">{{ Str::limit($childCategory->title, 25) }}</span>
                        <span class="category-count">{{ $childCategory->items_count }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            <div class="hidden-radio">
                <input type="radio" id="child_category_all" name="child_category" value="all" {{ !request()->filled('child_category') || request('child_category') == 'all' ? 'checked' : '' }}>
                @foreach($childCategories as $childCategory)
                <input type="radio" id="child_category_{{ $childCategory->id }}" name="child_category" value="{{ $childCategory->id }}" {{ request('child_category') == $childCategory->id ? 'checked' : '' }}>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif --}}
<ul class="category-list" up-hungry>
    <li>
        <a up-follow up-target=".collection-content"
           href="{{ route('categories.filter', array_merge(request()->query(), ['child_category' => 'all'])) }}"
           class="{{ !request()->filled('child_category') || request('child_category') == 'all' ? 'active' : '' }}">
            <span class="category-name">@lang('ecommerce::ecommerce.categories.all_categories')</span>
            <span class="category-count">{{ $childCategories->sum('items_count') }}</span>
        </a>
    </li>
    @foreach($childCategories as $category)
    <li>
        <a up-follow up-target=".collection-content"
           href="{{ route('categories.filter', array_merge(request()->query(), ['child_category' => $category->id])) }}"
           class="{{ request('child_category') == $category->id ? 'active' : '' }}">
            <span class="category-name">{{ Str::limit($category->title, 25) }}</span>
            <span class="category-count">{{ $category->items_count }}</span>
        </a>
    </li>
    @endforeach
</ul>
