<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title">
            @lang('marketplace::home.categories')
        </div>
        @MerchantHasRole(['create_menu'])
        <div class="prism-toggle">
            <a
                class="btn btn-primary-light m-1" wire:click="$emit('createCategory',{{ $menu_id }})">@lang('marketplace::home.Add New')<i class="bi bi-plus-lg ms-2"></i></a>
        </div>
        @endMerchantHasRole
    </div>
    <div class="card-body shadow rounded p-4">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" class="fw-bold">{{ __('marketplace::home.name') }}</th>
                        <th scope="col" class="fw-bold">{{ __('marketplace::home.actions') }}</th>
                    </tr>
                </thead>
                @if(count($this->categories) > 0)
                    <tbody>
                        @foreach($this->categories as $key => $category)
                            <tr>
                                <td>
                                    <a class="nav-link p-2 fs-14 fw-semibold @if($categorySelected == $category->id) active @endif" id="{{ $category->id }}" href="#" wire:click="$emit('setCategoryItems',{{ $category->id }})">
                                        {{ \Illuminate\Support\Str::of($category->title)->limit(30) }}
                                    </a>
                                </td>
                                <td>
                                    <div class="hstack gap-2 fs-15">

                                        <a class="btn btn-icon btn-sm btn-info-transparent rounded-pill" wire:click="$emit('updateCategory',{{ $category->id }}, {{ $menu_id }})" title="edit"><i class="ri-edit-line"></i></a>

                                        @MerchantHasRole(['delete_menu'])
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteCategory',{{ $category->id }}, {{ $menu_id }})"><i class="ri-delete-bin-line"></i></a>
                                        @endMerchantHasRole

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
        @if(!(count($this->categories) > 0))
            <p class="text-center mt-2 fs-15">{{ __('marketplace::home.noCategories') }}</p>
        @endif
    </div>
    @MerchantHasRole(['create_menu'])
    <livewire:marketplace::menu.component.category.modal.create />
    @endMerchantHasRole

    <livewire:marketplace::menu.component.category.modal.update />

    @MerchantHasRole(['delete_menu'])
    <livewire:marketplace::menu.component.category.modal.delete />
    @endMerchantHasRole

</div>
