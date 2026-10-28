<div>
    <div class="modal fade" id="updateRole" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('company::home.update')</h6>
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
                                            <div class="col-xl-12">
                                                <label for="role-name" class="form-label text-black">@lang('company::home.ROLE NAME') <span class="text-danger">*</span></label>
                                                <input type="text" wire:model.defer="name" class="form-control" id="role-name" placeholder="Name" value="">
                                                @error("name") <span
                                                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            </div>

                                            <h4 class="page-title fs-16">{{ __('company::home.permissions') }}</h4>
                                            @error("permissions") <span
                                                class="d-inline-block text-danger">{{ $message }}</span> @enderror
                                            <div class="row row-cols-12">
                                                @foreach($permissionsEdit as $key => $value)
                                                    <div class="col-4">
                                                        <div class="card custom-card  bg-blue-transparent">
                                                            <div class="card-header justify-content-between">
                                                                <div class="card-title">
                                                                    {{$key}}
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                @foreach($value as $k => $permission)
                                                                    <div class="form-check mb-2">
                                                                        <input
                                                                            class="form-check-input form-checked-success"
                                                                            type="checkbox"
                                                                            @if(in_array($permission['id'], $this->permissions)) checked @endif
                                                                            value="{{ $permission['id'] }}"
                                                                            wire:model="permissions"
                                                                            id="edit_{{ $key }}{{ $permission['id'] }}">
                                                                        @if(LaravelLocalization::getCurrentLocale() == 'en')
                                                                            <label class="form-check-label" style="cursor: pointer" for="edit_{{ $key }}{{ $permission['id'] }}">
                                                                                {{  ucfirst(explode('_', trim($permission['name']))[0]) }}
                                                                            </label>
                                                                        @elseif(LaravelLocalization::getCurrentLocale() == 'ar')
                                                                            <label class="form-check-label" style="cursor: pointer" for="edit_{{ $key }}{{ $permission['id'] }}">
                                                                                {{  ucfirst(explode('_', trim($permission['name_ar']))[0]) }}
                                                                            </label>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
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
                    @CompanyHasRole(['edit_roles'])
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="store">{{ __('company::home.Close') }}
                        </button>
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="store">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    {{ __('company::home.Save') }}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    {{ __('company::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>
                    </div>
                    @endCompanyHasRole
                </form>
            </div>
        </div>
    </div>

</div>
