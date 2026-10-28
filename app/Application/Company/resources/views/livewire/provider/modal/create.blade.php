<div>
    <div class="modal fade" id="createProvider"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('company::home.Create New Driver')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3 mt-2">
                                            <div class="col-xl-6">
                                                <label for="name" class="form-label text-black">@lang('company::home.Driver Name')</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                       id="name"
                                                       placeholder="Provider Name" wire:model.defer="name">
                                                @error("name") <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="email" class="form-label text-default">@lang('company::home.Email Address')</label>
                                                <input type="email"
                                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                                       id="email"
                                                       wire:model.defer="email"
                                                       placeholder="Email Address">
                                                @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="row gy-3 mt-2">
                                            <div class="col-xl-4">
                                                <label for="dialcode" class="form-label text-default">@lang('company::home.Dial Code')</label>
                                                <input type="number"
                                                       class="form-control form-control-lg @error('dialcode') is-invalid @enderror"
                                                       id="dialcode"
                                                       wire:model.defer="dialcode"
                                                       placeholder="Dial code">
                                                @error('dialcode') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <label for="phone" class="form-label text-default">@lang('company::home.Phone Number')</label>
                                                <input type="number"
                                                       class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                                       id="phone"
                                                       wire:model.defer="phone"
                                                       placeholder="Phone Number">
                                                @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                            </div>

                                            <div class="col-xl-4">
                                                <label for="active"
                                                       class="form-label text-black">@lang('company::home.Status')</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="active"
                                                           type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                    <label for="active" class="label-success"
                                                           wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row gy-3 mt-2">
                                            <div class="col-xl-6">
                                                <label for="password" class="form-label text-default">@lang('company::home.Password')</label>
                                                <div class="input-group">
                                                    <input
                                                        type="password"
                                                        class="form-control form-control-lg"
                                                        id="signup-password"
                                                        wire:model.defer="password"
                                                        placeholder="************">
                                                </div>
                                                @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                            </div>
                                            <div class="col-xl-6 mb-3">
                                                <label for="confirmpassword" class="form-label text-default">@lang('company::home.Confirm Password')</label>
                                                <div class="input-group">
                                                    <input
                                                        type="password"
                                                        class="form-control form-control-lg"
                                                        wire:model.defer="password_confirmation"
                                                        id="confirmpassword" placeholder="************">
                                                </div>
                                                @error('password') <span class="d-block text-danger">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">@lang('company::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('company::home.Save')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('company::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
