<div>
    <div class="modal fade" id="creatAdditionGroup" wire:ignore.self data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('admin::home.create_new')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5 mx-0">
                            <div class="col-12">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-3">
                                        <div class="row gy-3 mx-0">
                                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                                <div class="col-xl-6">
                                                    <label for="category-name" class="form-label text-black">@lang('admin::home.name') {{$properties['native']}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="category-name" placeholder="@lang('admin::home.name')" wire:model.defer="title.{{$localeCode}}">
                                                    @error("title." . $localeCode) <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                </div>
                                            @endforeach

                                            <div class="col-xl-2">
                                                <label for="order" class="form-label text-black">@lang('admin::home.Priority') <span class="text-danger">*</span></label>
                                                <input type="number" min="1" class="form-control" id="order" placeholder="1" wire:model.defer="order">
                                                @error("order")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-3">
                                                <label for="min_value" class="form-label text-black">@lang('admin::home.min_customer_choices') <span class="text-danger">*</span></label>
                                                <input type="number" min="0" class="form-control" id="min_value" value="{{ $multi == 0 ? 1 : 0}}" placeholder="{{ $multi == 0 ? 1 : 0}}" wire:model="min_value"  <?php if ($multi == 0) echo 'disabled'; ?>>
                                                @error("min_value")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-xl-3 p-1" >
                                                <label for="max_value" class="form-label text-black">@lang('admin::home.max_customer_choices')<span class="text-danger">*</span></label>
                                                <input type="number" min="0" class="form-control" id="max_value" value="{{$multi == 0 ? 1 : 0}}" placeholder="{{ $multi == 0 ? 1 : 0}}" wire:model="max_value"  <?php if ($multi == 0) echo 'disabled'; ?>>
                                                @error("max_value")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-xl-2">
                                                <label for="multi" class="form-label text-black">@lang('admin::home.Show on app') <span class="text-danger">*</span></label>
                                                <select class="form-control" id="multi" wire:change="changeMinMax" wire:model="multi">
                                                    <option value="">@lang('admin::home.Select')</option>
                                                    <option value="1">@lang('admin::home.Multi')</option>
                                                    <option value="0">@lang('admin::home.Single')</option>
                                                </select>
                                                @error("multi")
                                                <span class="text-danger d-block mt-2">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-xl-2">
                                                <label for="category-active" class="form-label text-black">@lang('admin::home.status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="category-active" type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                    <label for="category-active" class="label-success" wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                            </div>

                                            <div class="form-group d-flex flex-wrap align-items-center justify-content-between gap-2">
                                                <a href="#" wire:click.prevent="addOption()" class="btn btn-primary-light addition-group-add-option m-0">
                                                    <i class="la la-plus"></i> @lang('admin::home.add')
                                                </a>
                                                <span class="form-label mt-1 fs-12 op-5 text-info mb-0">* @lang('admin::home.you_should_select_at_least_one_option')</span>
                                            </div>

                                            @if($choices->isEmpty())
                                                <div class="alert alert-warning mb-0" role="alert">
                                                    @lang('admin::home.Create choice before addition group')
                                                </div>
                                            @endif

                                            <div class="table-responsive">

                                                <table class="table align-middle mb-0" style="min-width: 560px;">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">@lang('admin::home.name') <span class="text-danger">*</span></th>
                                                        <th scope="col">@lang('admin::home.price') <span class="text-danger">*</span></th>
                                                        <th scope="col">@lang('admin::home.Priority') <span class="text-danger">*</span></th>
                                                        <th scope="col">@lang('admin::home.delete')</th>
                                                    </tr>

                                                    </thead>

                                                    <tbody>
                                                    @error("options")
                                                    <tr>
                                                        <td colspan="4"><span class="text-danger">{{ $message }}</span></td>
                                                    </tr>
                                                    @enderror
                                                    @foreach($options as $index => $option)
                                                        <tr wire:key="addition-group-option-{{$index}}">
                                                            <td>
                                                                <input wire:model.defer="options.{{$index}}.id" type="hidden" />
                                                                <select wire:model.defer="options.{{$index}}.choice_id" class="form-control" aria-label="@lang('admin::home.name')">
                                                                    <option value="">@lang('admin::home.Select')</option>
                                                                    @foreach ($choices as $choice)
                                                                        <option value="{{ $choice->id }}">{{ $choice->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error("options." . $index . ".choice_id") <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                                            </td>

                                                            <td>
                                                                <input type="number" min="0" step="0.005" required class="form-control" id="choice-price-{{$index}}" aria-label="@lang('admin::home.price')" wire:model.defer="options.{{$index}}.price">
                                                                @error("options." . $index . ".price") <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                                            </td>

                                                            <td>
                                                                <input type="number" min="1" required class="form-control" id="choice-order-{{$index}}" aria-label="@lang('admin::home.Priority')" wire:model.defer="options.{{$index}}.order">
                                                                @error("options." . $index . ".order") <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                                            </td>
                                                            <td>
                                                                <a href="#" wire:click.prevent="deleteOption({{$index}})" class="btn btn-danger-light btn-icon btn-sm" aria-label="@lang('admin::home.delete')" title="@lang('admin::home.delete')">
                                                                    <i class="ri-delete-bin-line fs-15" aria-hidden="true"></i>
                                                                </a>
                                                            </td>


                                                        </tr>

                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">@lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success" @disabled($choices->isEmpty())>
                            <span class="indicator-label" wire:loading.class="d-none">
                                @lang('admin::home.Save')
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
