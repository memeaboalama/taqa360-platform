<div class="search-suggestions">
    @if($suggestions->count() > 0)
    <div class="suggestion-header">
        <small class="text-muted">Quick suggestions for "{{ $query }}"</small>
    </div>
    @foreach($suggestions as $suggestion)
    <a href="{{ $suggestion['url'] }}"
       class="suggestion-item"
       up-target=".main-content"
       up-history="true"
       up-dismiss=".search-suggestions-wrapper">
        <div class="suggestion-image">
            <img src="{{ $suggestion['image'] ?? asset('assets/images/pro/1.jpg') }}"
                 alt="{{ $suggestion['title'] }}"
                 onerror="this.src='{{ asset('assets/images/pro/1.jpg') }}'">
        </div>
        <div class="suggestion-details">
            <div class="suggestion-title">{{ Str::limit($suggestion['title'], 40) }}</div>
            <div class="suggestion-price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($suggestion['price']) }}</div>
        </div>
    </a>
    @endforeach
    <div class="suggestion-footer">
        <a href="{{ route('categories.search', ['q' => $query]) }}"
           class="view-all-results"
           up-target=".main-content"
           up-history="true"
           up-dismiss=".search-suggestions-wrapper">
           View all {{ $suggestions->count() }} results
        </a>
    </div>
    @else
    <div class="no-suggestions">
        <div class="text-center py-3">
            <i class="fa fa-search fa-2x text-muted mb-2"></i>
            <p class="text-muted mb-0">No products found for "{{ $query }}"</p>
        </div>
    </div>
    @endif
</div>

<style>
.search-suggestions {
    max-height: 400px;
    overflow-y: auto;
}

.suggestion-header {
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.suggestion-item {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    border-bottom: 1px solid #f5f5f5;
    text-decoration: none;
    color: #333;
    transition: background-color 0.2s;
}

.suggestion-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
    color: #333;
}

.suggestion-image {
    width: 40px;
    height: 40px;
    margin-inline-end: 12px;
    flex-shrink: 0;
}

.suggestion-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.suggestion-details {
    flex: 1;
}

.suggestion-title {
    font-size: 0.9rem;
    margin-bottom: 2px;
    line-height: 1.3;
}

.suggestion-price {
    font-size: 0.85rem;
    font-weight: bold;
    color: #007bff;
}

.suggestion-footer {
    padding: 10px 15px;
    border-top: 1px solid #eee;
    text-align: center;
}

.view-all-results {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}

.view-all-results:hover {
    text-decoration: underline;
}

.no-suggestions {
    padding: 20px;
}
</style>
