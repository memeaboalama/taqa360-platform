<div>
    <div class="modal fade" id="createEmployee" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> {{ __('marketplace::home.Add New') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:loading.attr="disabled" wire:target="store"></button>
                </div>
                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-6">
                                                <label for="employee-name" class="form-label text-black">{{ __('marketplace::home.name') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="employee-name"
                                                       placeholder="{{ __('marketplace::home.name') }}" wire:model.defer="name">
                                                @error('name') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <label for="employee-role" class="form-label text-black">{{ __('marketplace::home.ROLE NAME') }} <span class="text-danger">*</span></label>
                                                <select class="form-control @error('role') is-invalid @enderror" id="employee-role" wire:model.defer="role">
                                                    <option value="">-- {{ __('marketplace::home.ROLE NAME') }} --</option>
                                                    @foreach($rolesShow as $value)
                                                        <option value="{{ $value->id }}">{{ (strstr($value?->name , '_', true)) ? strstr($value?->name , '_', true) : $value?->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-2">
                                                <label for="category-active"
                                                       class="form-label text-black">{{ __('marketplace::home.status') }} <span class="text-danger">*</span></label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="category-active"
                                                           type="checkbox" {{ $status == 'active' ? 'checked' : '' }}
                                                    >
                                                    <label for="category-active" class="label-success"
                                                           wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                                @error('status') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="employee-email"
                                                       class="form-label text-black">{{ __('marketplace::home.Email Address') }} <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="employee-email"
                                                       wire:model.defer="email"
                                                       placeholder="{{ __('marketplace::home.Email Address') }}">
                                                @error('email') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>


                                            <div class="col-xl-6">
                                                <label for="employee-phone" class="form-label text-black">{{ __('marketplace::home.Phone Number') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" wire:model.defer="phone"
                                                       id="employee-phone"
                                                       placeholder="{{ __('marketplace::home.Phone Number') }}">
                                                @error('phone') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>


                                            <div class="col-xl-6">
                                                <label for="employee-password"
                                                       class="form-label text-black">{{ __('marketplace::home.Password') }} <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" id="employee-password"
                                                       placeholder="************"
                                                       wire:model.defer="password"
                                                >
                                                @error('password') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="employee-password-confirm" class="form-label text-black">{{ __('marketplace::home.Confirm Password') }}</label>
                                                <input type="password" class="form-control" id="employee-password-confirm"
                                                       placeholder="************"
                                                       wire:model.defer="password_confirmation">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store">{{ __('marketplace::home.Close') }}
                        </button>
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="store">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    {{ __('marketplace::home.Save') }}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    {{ __('marketplace::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
