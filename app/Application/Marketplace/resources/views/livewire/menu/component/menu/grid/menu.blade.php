<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title">
            @lang('marketplace::home.menus')
        </div>
        @MerchantHasRole(['create_menu'])
        <div class="prism-toggle">
            <button
                class="btn btn-primary-light m-1"
                data-bs-toggle="modal"
                data-bs-target="#createMenu">@lang('marketplace::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
        </div>
        @endMerchantHasRole
    </div>
    <div class="card-body shadow rounded p-4">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col" class="fw-bold">{{ __('marketplace::home.name') }}</th>
                    <th scope="col" class="fw-bold">{{ __('marketplace::home.status') }}</th>
                    <th scope="col" class="fw-bold">{{ __('marketplace::home.actions') }}</th>
                </tr>
                </thead>
                @if($this->menus && count($this->menus) > 0)
                    <tbody>
                    @foreach($this->menus as $key => $menu)
                        <tr>
                            <td>
                                <a class="nav-link p-2 fs-14 fw-semibold @if($menuSelected == $menu->id) active @endif" id="{{ $menu->id }}" href="#" wire:click="$emit('selectAndSet',{{ $menu }})">
                                    {{ \Illuminate\Support\Str::of($menu->title)->limit(30) }}
                                </a>
                            </td>
                            <td>
                                <h4 class="badge {{ $menu?->status->color() }}">{{ $menu?->status->trans() }}</h4>
                                @if($menu->inReview())
                                    <h4 class="badge bg-primary">{{ __("marketplace::home.in-review") }}</h4>
                                @else
                                    <h4 class="badge bg-success">{{ __("marketplace::home.accepted") }}</h4>
                                @endif
                            </td>
                            <td>
                                <div class="hstack gap-2 fs-15">
                                    <a class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
                                       wire:click="$emit('updateMenu',{{ $menu->id }})"
                                       title="edit"><i class="ri-edit-line"></i></a>
                                    @MerchantHasRole(['delete_menu'])
                                    <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteMenu',{{ $menu->id }})"><i class="ri-delete-bin-line"></i></a>
                                    @endMerchantHasRole
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
        @if(!($this->menus && count($this->menus) > 0))
            <p class="text-center mt-2 fs-15">{{ __('marketplace::home.noMenus') }}</p>
        @endif
    </div>
    @MerchantHasRole(['create_menu'])
        <livewire:marketplace::menu.component.menu.modal.create />
    @endMerchantHasRole
    <livewire:marketplace::menu.component.menu.modal.update />
    @MerchantHasRole(['delete_menu'])
        <livewire:marketplace::menu.component.menu.modal.delete />
    @endMerchantHasRole

</div>
