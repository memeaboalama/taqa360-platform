<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title">
            @lang('marketplace::home.items')
        </div>
        @MerchantHasRole(['create_menu'])
        @if($category_id)
        <div class="prism-toggle">
            <button
                class="btn btn-primary-light m-1"
                wire:click="$emit('createItem', {{ $menu_id }}, {{ $category_id }})">@lang('marketplace::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
        </div>
        @endif
        @endMerchantHasRole
    </div>
    <div class="card-body shadow rounded p-4">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th class="fw-bold">#</th>
                    <th scope="col" class="fw-bold">{{ __('marketplace::home.itemName') }}</th>
                    <th scope="col" class="fw-bold">{{ __('marketplace::home.status') }}</th>
                    <th scope="col" class="fw-bold">{{ __('marketplace::home.date') }}</th>
                    <th scope="col" class="fw-bold">{{ __('marketplace::home.actions') }}</th>
                </tr>
                </thead>
                @if($this->items && count($this->items) > 0)
                    <tbody>
                    @foreach($this->items as $key => $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ \Illuminate\Support\Str::of($item->title)->limit(30)  }}</td>
                            <td><h4 class="badge {{ $item?->status->color() }}">{{ $item?->status->trans() }}</h4></td>
                            <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->translatedFormat('l , j F Y') }}</td>
                            <td>
                                <div class="hstack gap-2 fs-15">
                                    <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-info-transparent rounded-pill" title="Show" wire:click="$emit('showItem',{{ $item->id }}, {{ $menu_id }})"><i class="ri-eye-2-line"></i></a>
                                    <a class="btn btn-icon btn-sm btn-info-transparent rounded-pill" wire:click="$emit('updateItem',{{ $item->id }}, {{ $menu_id }})" title="edit"><i class="ri-edit-line"></i></a>
                                    @MerchantHasRole(['delete_menu'])
                                    <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteItem',{{ $item->id }}, {{ $menu_id }})"><i class="ri-delete-bin-line"></i></a>
                                    @endMerchantHasRole

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
        @if(!($this->items && count($this->items) > 0))
        <div class="align-content-center">
            <p class="text-center mt-2 fs-15">{{ __('marketplace::home.noItems') }}</p>
        </div>
        @endif

    </div>

    @MerchantHasRole(['create_menu'])
    <livewire:marketplace::menu.component.item.modal.create :category_id="$category_id"  />
    @endMerchantHasRole

    <livewire:marketplace::menu.component.item.modal.update :category_id="$category_id"  />

    <livewire:marketplace::menu.component.item.modal.show />

    @MerchantHasRole(['delete_menu'])
    <livewire:marketplace::menu.component.item.modal.delete :category_id="$category_id"  />
    @endMerchantHasRole

</div>
