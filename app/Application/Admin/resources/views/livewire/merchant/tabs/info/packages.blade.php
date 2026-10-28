<div wire:ignore.self>

    <div class="mt-4">
        <div class="row gy-3 mt-3">
            <!-- Start:: row-2 -->
            <div class="row justify-content-center mb-5">
                <div class="col-xxl-8 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="container-lg">
                        <div class="row justify-content-center">
                            <div class="col-xl-9">
                                <h5 class="fw-semibold text-center mb-5"> @lang('admin::home.Packages') </h5>
                            </div>
                        </div>
                        <div class="card custom-card overflow-hidden">
                            <div class="card-body p-0">
                                <div class="tab-content" id="myTabContent">
                                    <div class="row">
                                        @foreach($packages as $package)
                                            <br>
                                            <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-5 col-sm-12 pe-2 border border-primary" style="margin-right: 10px; margin-top: 10px">
                                                <div class="p-4">
                                                    <h6 class="text-center " style="font-size: 20px;color:rgb(75, 166, 38) ;font-weight:700;">{{ $package->title }}</h6>
                                                    <div class="py-4 d-flex align-items-center justify-content-center">
                                                        <div class="text-center ">
                                                            <p class="fs-15 mb-0" style="font-weight:700">({{ $package->commission }}%) @lang('admin::home.commission')</p>
                                                        </div>
                                                    </div>
                                                    <p class="fs-12 px-3 pt-3 mb-0 text-center " style="text-justify: inter-character" >{{ $package->description }}</p>
                                                    <div class="d-grid mt-2">
                                                        @if($package->id == $selected_package)
                                                            <button wire:click="$emit('selectPackage',{{ $package->id }})"
                                                                    class="btn btn-primary btn-wave"> @lang('admin::home.Selected')</button>
                                                        @else
                                                            <button wire:click="$emit('selectPackage',{{ $package->id }})"
                                                                    class="btn btn-primary-light btn-wave"> @lang('admin::home.Select')</button>
                                                        @endif

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
        </div>
    </div>

</div>
