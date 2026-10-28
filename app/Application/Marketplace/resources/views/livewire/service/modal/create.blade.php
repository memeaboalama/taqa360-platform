<div>
    <div class="modal fade" id="createMenu" wire:ignore.self data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('marketplace::home.Create New Menu')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <div class="col-xl-6">
                                                    <label for="service-name" class="form-label text-black">@lang('marketplace::home.Menu Name') {{$properties['native']}}</label>
                                                    <input type="text" class="form-control" id="service-name" placeholder="Name" wire:model.defer="title.{{$localeCode}}">
                                                    @error("title." . $localeCode)
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>


                                        <div class="row gy-3 mt-2">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <div class="col-xl-6">
                                                    <label for="description" class="form-label text-black">@lang('marketplace::home.Description') {{$properties['native']}}</label>
                                                    <textarea id="description" class="form-control text-black bg-gray-200" rows="7" placeholder="Enter helpful Description ......." wire:model.defer="description.{{$localeCode}}"></textarea>
                                                    @error("description." . $localeCode)
                                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="row gy-3 mt-2">
                                            <div class="col-xl-6">
                                                <label for="default_price" class="form-label text-black">@lang('marketplace::home.Default Price')</label>
                                                <input type="number" class="form-control" min="0" step="0.05" id="default_price" placeholder="Default Price" wire:model.defer="default_price">
                                                @error("default_price")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="service-active" class="form-label text-black">@lang('marketplace::home.status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="service-active" type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                    <label for="service-active" class="label-success" wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row gy-3 mt-2">
                                            <div class="col-xl-12">
                                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="">
                                                    <thead>
                                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                        <th class="min-w-250px">Categories</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="fw-bold text-gray-600">
                                                    @foreach ($categories as $key => $value)
                                                        <tr>
                                                            <td width="2%">
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input bg-gray-300" type="checkbox" wire:model="merchant_category_ids" value="{{ $key }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex">
                                                                        <span class="symbol symbol-50px">
                                                                            <span
                                                                                class="avatar avatar-lg bg-secondary">
                                                                                {{ \Str::substr($value,0,2) }}
                                                                            </span>
                                                                        </span>
                                                                    <div class="ms-5">
                                                                        <!--begin::Title-->
                                                                        <div class="text-gray-800 fs-6 fw-bolder mb-1" >
                                                                            <span>{{ $value }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>


                                                @error("merchant_category_ids")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">@lang('marketplace::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span class="indicator-label" wire:loading.class="d-none">
                                @lang('marketplace::home.Save')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
