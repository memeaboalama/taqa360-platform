@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.showMerchant'))

@push('css')
    <style>
        #merchant-detail-page #myTab .nav-link {
            color: rgb(var(--primary-rgb)) !important;
        }

        #merchant-detail-page #myTab .nav-link.active {
            background-color: rgb(var(--primary-rgb)) !important;
            border-color: rgb(var(--primary-rgb)) !important;
            color: #fff !important;
        }

        #merchant-detail-page .btn-primary-light,
        #merchant-detail-page .btn-primary-light i {
            color: rgb(var(--primary-rgb)) !important;
        }
    </style>
@endpush

@section('content')
    <div id="merchant-detail-page" class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-0">
                        <div
                            class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                            <div>
                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab"
                                    role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="info-tab" data-bs-toggle="tab"
                                                data-bs-target="#info-tab-pane" type="button" role="tab"
                                                aria-controls="info-tab-pane" aria-selected="false"><i
                                                class="ri-bill-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.merchantInfo') }}
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="branches-tab" data-bs-toggle="tab"
                                                data-bs-target="#branches-tab-pane" type="button" role="tab"
                                                aria-controls="info-tab-pane" aria-selected="false"><i
                                                class="ri-gps-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.branches') }}
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="files-tab" data-bs-toggle="tab"
                                                data-bs-target="#files-tab-pane" type="button" role="tab"
                                                aria-controls="files-tab-pane" aria-selected="true"><i
                                                class="ri-file-3-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.files') }}
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="menu-tab" data-bs-toggle="tab"
                                                data-bs-target="#menu-tab-pane" type="button" role="tab"
                                                aria-controls="menu-tab-pane" aria-selected="true"><i
                                                class="ri-file-3-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.menu') }}
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="additionGroups-tab" data-bs-toggle="tab"
                                                data-bs-target="#additionGroups-tab-pane" type="button" role="tab"
                                                aria-controls="additionGroups-tab-pane" aria-selected="true"><i
                                                class="ri-add-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.additionGroups') }}
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="choices-tab" data-bs-toggle="tab"
                                                data-bs-target="#choices-tab-pane" type="button" role="tab"
                                                aria-controls="choices-tab-pane" aria-selected="true"><i
                                                class="ri-bookmark-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.menuChoices') }}
                                        </button>
                                    </li>


                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="iban-tab" data-bs-toggle="tab"
                                                data-bs-target="#iban-tab-pane" type="button" role="tab"
                                                aria-controls="iban-tab-pane" aria-selected="true"><i
                                                class="ri-bank-card-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.iban info') }}
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="packages-tab" data-bs-toggle="tab"
                                                data-bs-target="#packages-tab-pane" type="button" role="tab"
                                                aria-controls="packages-tab-pane" aria-selected="false"><i
                                                class="bx bxs-badge-dollar me-1 align-middle d-inline-block"></i>{{ __('admin::home.Packages') }}</button>
                                    </li>

                                </ul>
                            </div>
                            <div>

                                <div class="progress progress-xs progress-animate">
                                    <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="30"
                                         aria-valuemin="0" aria-valuemax="100" style="width: 30%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade " id="info-tab-pane" role="tabpanel"
                                     aria-labelledby="info-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.info :merchant_id="$id"/>
                                </div>

                                <div class="tab-pane fade" id="branches-tab-pane" role="tabpanel"
                                     aria-labelledby="branches-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.branches.track :merchant_id="$id"/>
                                </div>

                                <div class="tab-pane fade" id="files-tab-pane" role="tabpanel"
                                     aria-labelledby="files-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.files :merchant_id="$id"/>
                                </div>

                                <div class="tab-pane fade show active" id="menu-tab-pane" role="tabpanel"
                                     aria-labelledby="menu-tab" wire:ignore.self>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <!-- Start:: row-3 -->
                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <livewire:admin::merchant.tabs.menu.component.menu.grid.menu :merchant_id="$id"/>
                                                </div>

                                                <div class="col-xl-3">
                                                    <livewire:admin::merchant.tabs.menu.component.category.grid.category :merchant_id="$id"/>
                                                </div>

                                                <div class="col-xl-5">
                                                    <livewire:admin::merchant.tabs.menu.component.item.grid.item :merchant_id="$id"/>
                                                </div>

                                            </div>
                                            <!-- End:: row-3 -->

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="additionGroups-tab-pane" role="tabpanel"
                                     aria-labelledby="additionGroups-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.groups :merchant_id="$id"/>
                                </div>

                                <div class="tab-pane fade" id="choices-tab-pane" role="tabpanel"
                                     aria-labelledby="choices-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.choices :merchant_id="$id"/>
                                </div>

                                <div class="tab-pane fade p-0 border-0" id="iban-tab-pane"
                                     aria-labelledby="iban-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.iban :merchant_id="$id" />
                                </div>

                                <div class="tab-pane fade p-0 border-0" id="packages-tab-pane"
                                     aria-labelledby="iban-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.packages :merchant_id="$id" />
                                    <livewire:admin::merchant.tabs.info.package.select :merchant_id="$id" />
                                </div>

                                @include('catalog::item.show',['path'=>'admin','userType'=>'admin',])

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection



@push('js')          
<script>
    
    $(document).ready(function() {

        $(document).on('click','a.btn.view-item-details-btn', function() {
            const itemId = $(this).data('item_id');
            const itemName = $(this).data('item_name');
            initItemInfoModal({id: itemId,name:itemName,userType: 'admin',path:'admin'});
        });
     
    });

</script>
@endpush