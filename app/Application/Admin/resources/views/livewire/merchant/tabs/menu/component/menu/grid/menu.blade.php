<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title">
            @lang('admin::home.menus')
        </div>
        <div class="prism-toggle">
            <button
                class="btn btn-primary-light m-1"
                data-bs-toggle="modal"
                data-bs-target="#createMenu">@lang('admin::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
        </div>
    </div>
    <div class="card-body shadow rounded p-4">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col" class="fw-bold">{{ __('admin::home.name') }}</th>
                    <th scope="col" class="fw-bold">{{ __('admin::home.status') }}</th>
                    <th scope="col" class="fw-bold">{{ __('admin::home.actions') }}</th>
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
                                    <h4 class="badge bg-primary">{{ __("admin::home.in-review") }}</h4>
                                @else
                                    <h4 class="badge bg-success">{{ __("admin::home.accepted") }}</h4>
                                @endif

                                @if($menu->trashed())
                                    <h4 class="badge bg-danger">{{ __("admin::home.deleted") }}</h4>
                                @endif
                            </td>
                            <td>
                                <div class="hstack gap-2 fs-15">
                                    @if($menu->inReview() && !$menu->trashed())
                                    <a
                                        href="javascript:void(0);"
                                        class="btn btn-icon btn-sm btn-success-transparent rounded-pill"
                                        title="{{ __("admin::home.accept") }}" wire:click="$emit('acceptMenu',{{ $menu->id }})"><i class="ri-check-double-fill"></i></a>
                                    @endif

                                    <a class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
                                       wire:click="$emit('updateMenu',{{ $menu->id }})"
                                       title="edit"><i class="ri-edit-line"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="{{ __("admin::home.Delete") }}" wire:click="$emit('deleteMenu',{{ $menu->id }})"><i class="ri-delete-bin-line"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
        @if(!($this->menus && count($this->menus) > 0))
            <p class="text-center mt-2 fs-15">{{ __('admin::home.noMenus') }}</p>
        @endif
    </div>
    <livewire:admin::merchant.tabs.menu.component.menu.modal.create :merchant_id="$merchantId"/>
    <livewire:admin::merchant.tabs.menu.component.menu.modal.update :merchant_id="$merchantId"/>
    <livewire:admin::merchant.tabs.menu.component.menu.modal.delete :merchant_id="$merchantId"/>
    <livewire:admin::merchant.tabs.menu.component.menu.modal.accept :merchant_id="$merchantId"/>
</div>
