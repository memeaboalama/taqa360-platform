<div>
    <div class="modal fade" id="assignProvider" wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Assign Provider</h6>
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
                                                <label for="provider"
                                                       class="form-label text-black">Provider Name</label>
                                                <select
                                                    wire:model.defer="provider"
                                                    id="provider"
                                                    class="form-control">
                                                    <option selected value="">---@lang('marketplace::home.Select')
                                                        provider---
                                                    </option>
                                                    @foreach($providers as $provider)
                                                        <option
                                                            value="{{ $provider?->id }}">{{ $provider?->name }} {{ $provider?->company_id ? ' - ' . $provider?->company?->company_name : '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('provider') <span
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
                                    @lang('marketplace::home.Save')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none">
                                    @lang('marketplace::home.Wait') <span
                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
