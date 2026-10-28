@if(isset($childCategories) && $childCategories->count() > 0)
<ul class="sidebar-list">
    <li>
        <a class="sidebar-link {{ !isset($selectedChildCategory) || $selectedChildCategory == 'all' ? 'active' : '' }}" onclick="selectChildCategory('all')">
            <span>@lang('ecommerce::ecommerce.categories.all_categories')</span>
        </a>
    </li>
    @foreach($childCategories as $category)
    <li>
        <a class="sidebar-link {{ isset($selectedChildCategory) && $selectedChildCategory == $category->id ? 'active' : '' }}" onclick="selectChildCategory('{{ $category->id }}')">
            <span>{{ Str::limit($category->title, 25) }}</span>
            <span class="sidebar-count">{{ $category->items_count ?? 0 }}</span>
        </a>
    </li>
    @endforeach
</ul>
@else
    <p class="text-muted text-center py-2 mb-0"><small>@lang('ecommerce::ecommerce.categories.no_subcategories')</small></p>
@endif
