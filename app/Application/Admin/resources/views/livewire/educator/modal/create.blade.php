<div>
    <div class="modal fade" id="createEducator" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Create new educator')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:target="store" wire:loading.attr="disabled"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-xl-6">
                                <label for="name" class="form-label text-black">{{__('admin::home.Educator Name')}} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name"
                                       placeholder="{{__('admin::home.Educator Name')}}" wire:model.defer="name">
                                @error('name') <span class="text-danger d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-6">
                                <label for="email" class="form-label text-black">{{__('admin::home.Email')}} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" wire:model.defer="email"
                                       placeholder="{{__('admin::home.Email')}}">
                                @error('email') <span class="text-danger d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-6">
                                <label for="phone" class="form-label text-black">@lang('admin::home.Phone Number') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.defer="phone" id="phone"
                                       placeholder="@lang('admin::home.Phone Number')">
                                @error('phone') <span class="text-danger d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-6">
                                <label for="password" class="form-label text-black">@lang('admin::home.Password') <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" wire:model.defer="password"
                                       placeholder="************">
                                @error('password') <span class="text-danger d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-xl-6">
                                <label for="password-confirm" class="form-label text-black">
                                    @lang('admin::home.Confirm Password') <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password-confirm"
                                       placeholder="************" wire:model.defer="password_confirmation">
                            </div>

                            <div class="col-xl-6">
                                <label for="category-active"
                                       class="form-label text-black">@lang('admin::home.status')</label>
                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                    <input id="category-active" type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                    <label for="category-active" class="label-success"
                                           wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                </div>
                                @error('status') <span class="text-danger d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"
                                wire:target="store" wire:loading.attr="disabled">
                            @lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success" wire:target="store" wire:loading.attr="disabled">
                            <span class="indicator-label" wire:loading.remove wire:target="store">
                                @lang('admin::home.Save')
                            </span>
                            <span class="indicator-label" wire:loading wire:target="store">
                                @lang('admin::home.Save') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
