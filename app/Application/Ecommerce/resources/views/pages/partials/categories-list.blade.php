<ul class="category-list">
    <li>
        <a data-target-radio="{{ $type }}_all" class="{{ !request()->filled($type) || request($type) == 'all' ? 'active' : '' }}">
            <span class="category-name">{{ $all_text }}</span>
        </a>
    </li>
    @foreach($categories as $category)
    <li>
        <a data-target-radio="{{ $type }}_{{ $category->id }}" class="{{ request($type) == $category->id ? 'active' : '' }}">
            <span class="category-name">{{ Str::limit($category->title, 25) }}</span>
            <span class="category-count">{{ $category->items_count }}</span>
        </a>
    </li>
    @endforeach
</ul>
<div class="hidden-radio">
    <input type="radio" id="{{ $type }}_all" name="{{ $type }}" value="all" {{ !request()->filled($type) || request($type) == 'all' ? 'checked' : '' }}>
    @foreach($categories as $category)
    <input type="radio" id="{{ $type }}_{{ $category->id }}" name="{{ $type }}" value="{{ $category->id }}" {{ request($type) == $category->id ? 'checked' : '' }}>
    @endforeach
</div>
