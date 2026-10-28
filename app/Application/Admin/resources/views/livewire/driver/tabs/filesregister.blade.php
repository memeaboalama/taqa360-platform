<div>
    <div class="mt-0">
            <div class="row gy-3 mt-3" style= "overflow-y: scroll; height:600px">
                <div class="col-xl-12">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('admin::home.license_copy')
                            </div>
                        </div>
                      

                        @if(isset($license_copy_file))
                            @if($license_copy_file)
                                @if(in_array(substr($license_copy_original_name, strpos($license_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $license_copy_file }}" target="_blank">{{ $license_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $license_copy_file }}">
                                            <img src="{{ $license_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('admin::home.license_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>


                <div class="col-xl-12">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('admin::home.car_copy')
                            </div>
                        </div>
                     

                        @if(isset($car_copy_file))
                            @if($car_copy_file)
                                @if(in_array(substr($car_copy_original_name, strpos($car_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $car_copy_file }}" target="_blank">{{ $car_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $car_copy_file }}">
                                            <img src="{{ $car_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('admin::home.car_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                        @endif

                    </div>
                </div>


                <div class="col-xl-12">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('admin::home.residence_permit_copy')
                            </div>
                        </div>
                     
                        @if(isset($residence_permit_copy_file))
                            @if($residence_permit_copy_file)
                                @if(in_array(substr($residence_permit_copy_original_name, strpos($residence_permit_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $residence_permit_copy_file }}" target="_blank">{{ $residence_permit_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $residence_permit_copy_file }}">
                                            <img src="{{ $residence_permit_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('admin::home.residence_permit_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('admin::home.iban_copy')
                            </div>
                        </div>
                    
                        @if(isset($iban_copy_file))
                            @if($iban_copy_file)
                                @if(in_array(substr($iban_copy_original_name, strpos($iban_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $iban_copy_file }}" target="_blank">{{ $iban_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $iban_copy_file }}">
                                            <img src="{{ $iban_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('admin::home.iban_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card custom-card border-box-1">
                        <div class="card-header">
                            <div class="card-title">
                                @lang('admin::home.car_registration_copy')
                            </div>
                        </div>
                    
                        @if(isset($car_registration_copy_file))
                            @if($car_registration_copy_file)
                                @if(in_array(substr($car_registration_copy_original_name, strpos($car_registration_copy_original_name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
                                    <h5 class="mt-5 mx-auto"><a href="{{ $car_registration_copy_file }}" target="_blank">{{ $car_registration_copy_original_name }}</a></h5>
                                @else
                                    <div class="mb-2 mx-auto" style="width:150px;">
                                        <a href="{{ $car_registration_copy_file }}">
                                            <img src="{{ $car_registration_copy_file }}"
                                                 class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                                 alt="@lang('admin::home.car_registration_copy')">
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="mb-2 mx-auto mt-2" style="width:150px;">@lang('admin::home.No file attached yet')</div>
                        @endif
                    </div>
                </div>
            </div>


            
       
    </div>
</div>
