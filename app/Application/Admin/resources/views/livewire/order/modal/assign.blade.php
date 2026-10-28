<div>
    <div class="modal fade" id="assignEmployee"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Assign Employee</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-12">
                                                <label for="branch-name"
                                                       class="form-label text-black">@lang('admin::home.Branch Name')</label>
                                               <input type="text" class="form-control" readonly value="{{ $order?->branch?->title }}">
                                            </div>
                                            <div class="col-xl-12">
                                                <label for="employee"
                                                       class="form-label text-black">@lang('admin::home.Employee Name')</label>
                                                <select
                                                    wire:model.defer="employee"
                                                    id="employee"
                                                    class="form-control">
                                                    <option selected value="">---@lang('admin::home.Select') @lang('admin::home.Employee')---</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee?->id }}">{{ $employee?->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('employee') <span
                                                    class="text-danger d-block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">@lang('marketplace::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
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
