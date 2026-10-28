@php
    $groups = $placement->settings['groups'] ?? [];
@endphp

@if(!empty($groups))
<section class="product_sec pb-0">
    <div class="collection-product-wrapper">
        <div class="row product-wrapper-grid f_product">
            <div class="col-12">
                <div class="title">
                    <h2>{{ $placement->getTranslation('name', app()->getLocale()) }}</h2>
                </div>
            </div>
            <div class="col position-relative">
                <div class="theme-tab">
                    <ul class="tabs tab-title">
                        @foreach($groups as $index => $group)
                            <li class="{{ $loop->first ? 'current' : '' }}" id="tab-li-{{ $placement->id }}-{{ $index }}">
                                <a href="{{ route('home.tab.filter', ['placementId' => $placement->id, 'groupKey' => $group['key']]) }}"
                                   up-target="#product-grid-target-{{ $placement->id }}"
                                   up-preload
                                   onclick="document.querySelectorAll('#tab-li-{{ $placement->id }}-{{ $index }}').forEach(el => el.classList.add('current')); document.querySelectorAll('.tabs li:not(#tab-li-{{ $placement->id }}-{{ $index }})').forEach(el => el.classList.remove('current'));">
                                    {{ $group['title'][app()->getLocale()] ?? $group['title']['en'] ?? '' }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content-cls" id="product-grid-target-{{ $placement->id }}">
                        @php
                            $firstGroup = $groups[0] ?? null;
                            $products = $firstGroup
                                ? $placement->activeProductContents->where('group_key', $firstGroup['key'])->pluck('product')->filter()
                                : collect();
                        @endphp
                        @include('ecommerce::partials.home-sections.product-grid', ['products' => $products, 'placement' => $placement])
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
