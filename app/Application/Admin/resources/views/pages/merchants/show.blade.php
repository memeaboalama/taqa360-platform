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
    <div class="mb-3">
        <a href="{{ url('admin/merchants') }}" class="btn btn-primary-light">
            <i class="ri-arrow-go-back-line me-1"></i>{{ __('admin::home.back_to_merchants') }}
        </a>
    </div>
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
                                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab"
                                                data-bs-target="#info-tab-pane" type="button" role="tab"
                                                aria-controls="info-tab-pane" aria-selected="true"><i
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
                                        <button class="nav-link" id="images-tab" data-bs-toggle="tab"
                                                data-bs-target="#images-tab-pane" type="button" role="tab"
                                                aria-controls="images-tab-pane" aria-selected="false"><i
                                                class="ri-image-3-fill me-1 align-middle d-inline-block"></i>{{ __('admin::home.Images') }}
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="products-tab" data-bs-toggle="tab"
                                                data-bs-target="#products-tab-pane" type="button" role="tab"
                                                aria-controls="products-tab-pane" aria-selected="false"><i
                                                class="ri-shopping-bag-3-line me-1 align-middle d-inline-block"></i>{{ __('admin::home.products') }}
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

                                <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel"
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

                                <div class="tab-pane fade" id="images-tab-pane" role="tabpanel"
                                     aria-labelledby="images-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.files :merchant_id="$id" mode="images"/>
                                </div>

                                <div class="tab-pane fade" id="products-tab-pane" role="tabpanel"
                                     aria-labelledby="products-tab" wire:ignore.self>
                                    <div class="card custom-card">
                                        <div class="card-header justify-content-between">
                                            <div class="card-title">{{ __('admin::home.products') }}</div>
                                            <button type="button" class="btn btn-primary-light m-1"
                                                    wire:click="$emit('createProduct', {{ (int) $id }})">
                                                {{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <livewire:marketplace::product.table.all :merchant_id="$id" :key="'merchant-products-'.$id"/>
                                            </div>
                                        </div>
                                        <livewire:marketplace::product.modal.create :merchant_id="$id" :key="'merchant-product-create-'.$id"/>
                                        <livewire:marketplace::product.modal.update :merchant_id="$id" :key="'merchant-product-update-'.$id"/>
                                        <livewire:marketplace::product.modal.show :merchant_id="$id" :key="'merchant-product-show-'.$id"/>
                                        <livewire:marketplace::product.modal.delete :merchant_id="$id" :key="'merchant-product-delete-'.$id"/>
                                    </div>
                                </div>

                                <div class="tab-pane fade p-0 border-0" id="iban-tab-pane"
                                     aria-labelledby="iban-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.iban :merchant_id="$id" />
                                </div>

                                <div class="tab-pane fade p-0 border-0" id="packages-tab-pane"
                                     aria-labelledby="packages-tab" wire:ignore.self>
                                    <livewire:admin::merchant.tabs.info.packages :merchant_id="$id" />
                                    <livewire:admin::merchant.tabs.info.package.select :merchant_id="$id" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection


