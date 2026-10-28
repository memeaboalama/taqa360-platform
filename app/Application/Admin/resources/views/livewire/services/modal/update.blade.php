<div>
    <div class="modal fade" id="updateEmployee" wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> Create new employee</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-6">
                                                <label for="employee-name" class="form-label text-black">Employee
                                                    Name</label>
                                                <input type="text" class="form-control" id="employee-name"
                                                       placeholder="Name" wire:model.defer="name">
                                                <label for="employee-name-add"
                                                       class="form-label mt-1 fs-12 op-5 text-info mb-0">*Employee
                                                    Name
                                                    should not exceed 30 characters</label>
                                                @error('name') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

{{--                                            <div class="col-xl-6">--}}
{{--                                                <label for="employee-role" class="form-label text-black">Role</label>--}}
{{--                                                <select class="form-control @error('role') is-invalid @enderror" id="employee-role" wire:model.defer="role">--}}
{{--                                                    <option value="">-- select role --</option>--}}
{{--                                                    @foreach($rolesShow as $value)--}}
{{--                                                        <option value="{{ $value->id }}">{{ $value?->name }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                                @error('role') <span--}}
{{--                                                    class="text-danger  d-block">{{ $message }}</span> @enderror--}}
{{--                                            </div>--}}


                                            <div class="col-xl-6">
                                                <label for="employee-email"
                                                       class="form-label text-black">Email</label>
                                                <input type="email" class="form-control" id="employee-email"
                                                       wire:model.defer="email"
                                                       placeholder="email">
                                                @error('email') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>


{{--                                            <div class="col-xl-6">--}}
{{--                                                <label for="employee-phone" class="form-label text-black">Phone--}}
{{--                                                    Number</label>--}}
{{--                                                <input type="text" class="form-control" wire:model.defer="phone"--}}
{{--                                                       id="employee-phone"--}}
{{--                                                       placeholder="phone">--}}
{{--                                                @error('phone') <span--}}
{{--                                                    class="text-danger  d-block">{{ $message }}</span> @enderror--}}
{{--                                            </div>--}}


                                            <div class="col-xl-6">
                                                <label for="employee-password"
                                                       class="form-label text-black">Password</label>
                                                <input type="password" class="form-control" id="employee-password"
                                                       placeholder="************"
                                                       wire:model.defer="password"
                                                >
                                                @error('password') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="employee-password-confirm" class="form-label text-black">Confirm
                                                    Password</label>
                                                <input type="password" class="form-control" id="employee-password-confirm"
                                                       placeholder="************"
                                                       wire:model.defer="password_confirmation">
                                            </div>

                                            {{--                                        <div class="col-xl-6">--}}
                                            {{--                                            <label for="branch-country" class="form-label text-black">Role</label>--}}
                                            {{--                                            <select class="form-control" data-trigger name="country_id" id="branch-country">--}}
                                            {{--                                                <option >Select</option>--}}
                                            {{--                                                <option >Editor</option>--}}
                                            {{--                                                <option >Viewer</option>--}}
                                            {{--                                                <option >Super admin</option>--}}
                                            {{--                                            </select>--}}
                                            {{--                                        </div>--}}

                                            <div class="col-xl-6">
                                                <label for="category-active"
                                                       class="form-label text-black">Status</label>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    Save
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                    Wait <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
