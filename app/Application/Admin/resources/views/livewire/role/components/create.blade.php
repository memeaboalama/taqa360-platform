<div>
    <form wire:submit.prevent="store" enctype="multipart/form-data">
        <div class="row gy-3">
            <div class="col-xl-2">
                <div class="col-xl-12">
                    <label for="role-name" class="form-label text-black">{{ __('admin::home.Role Name') }}</label>
                    <input type="text" wire:model.defer="name" class="form-control @error("name") is-invalid @enderror" id="role-name" placeholder="@lang('admin::home.ROLE NAME')" value="">
                    @error("name") <span
                        class="d-inline-block text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-xl-10">
                <h4 class="text-center fw-bolder fs-16">{{ __('admin::home.Permissions') }}</h4>
                @error("permissions") <span
                    class="d-inline-block text-danger">{{ $message }}</span> @enderror
                <div class="row row-cols-12 mt-5">
                    @foreach($permissionsShow as $key => $value)
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
                                                value="{{ $permission['id'] }}"
                                                wire:model.defer="permissions.{{$permission['id']}}"
                                                id="add_{{ $key }}{{ $permission['id'] }}">
                                            @if(LaravelLocalization::getCurrentLocale() == 'en')
                                                <label class="form-check-label" style="cursor: pointer" for="add_{{ $key }}{{ $permission['id'] }}">
                                                    {{  ucfirst(explode('_', trim($permission['name']))[0]) }}
                                                </label>
                                            @elseif(LaravelLocalization::getCurrentLocale() == 'ar')
                                                <label class="form-check-label" style="cursor: pointer" for="add_{{ $key }}{{ $permission['id'] }}">
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
                <div class="row">
                    <div class="col-xl-6">
                        <button type="submit" class="btn btn-primary">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    {{ __('admin::home.Save') }}
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                    {{ __('admin::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

