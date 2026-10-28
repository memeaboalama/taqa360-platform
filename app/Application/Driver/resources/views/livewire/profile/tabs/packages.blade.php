<div class="tab-pane fade p-0 border-0" id="packages-tab-pane"
     role="tabpanel" aria-labelledby="packages-tab" tabindex="0" wire:ignore.self>

    <div class="mt-4">
        <div class="row gy-3 mt-3">
            <!-- Start:: row-2 -->
            <div class="row justify-content-center mb-5">
                <div class="col-xxl-8 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="container-lg">
                        <div class="row justify-content-center">
                            <div class="col-xl-9">
                                <h5 class="fw-semibold text-center mb-5"> @lang('driver::home.Select Package') </h5>
                            </div>
                        </div>
                        <div class="card custom-card">
                            <div class="card-body p-0">
                                <div class="tab-content" id="myTabContent">
                                    <div class="row">
                                        @foreach($packages as $package)
                                            <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-5 col-sm-12 pe-2 border border-primary" style="margin-right: 10px; margin-top: 10px">
                                                <div class="p-4">
                                                    <h6 class="fw-semibold text-center">{{ $package->title }}</h6>
                                                    <div class="py-4 d-flex align-items-center justify-content-center">
                                                        <div class="text-end ms-5">
                                                            <p class="fs-15 fw-semibold mb-0">{{ $package->commission }} (%) @lang('driver::home.commission')</p>
                                                        </div>
                                                    </div>
                                                    <p class="text-center fs-12 px-3 pt-3 mb-0">{{ $package->description }}</p>
                                                    <div class="d-grid mt-2">
                                                        @if($package->id == $selected_package)
                                                            <button wire:click="$emit('selectPackage',{{ $package->id }})"
                                                                class="btn btn-primary btn-wave"> @lang('driver::home.Selected')</button>
                                                        @else
                                                            @DriverHasRole(['edit packages_profile'])
                                                            @if(!$selected_package)
                                                            <button wire:click="$emit('selectPackage',{{ $package->id }})"
                                                                    class="btn btn-primary-light btn-wave"> @lang('driver::home.Select')</button>
                                                            @endif
                                                            @endDriverHasRole
                                                        @endif
                                                            <button wire:click="$emit('selectPackage',{{ $package->id }})"
                                                                    class="btn btn-info-light btn-wave mt-2"> @lang('driver::home.Terms & Conditions')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-2 -->
        </div>
    </div>

</div>
