<div class="mt-4">
    <div class="row g-4">
        <div class="{{ $merchantTempIban ? 'col-12 col-xl-6' : 'col-12' }}">
            <div class="card custom-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('admin::home.current_iban_info')</h5>
                </div>
                <div class="card-body">
                    <form method="POST" wire:submit.prevent="updateInfo" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="current_bank_name" class="form-label text-default">{{ __('admin::home.bank_name') }}</label>
                                <input type="text"
                                       class="form-control @error('current_bank_name') is-invalid @enderror"
                                       id="current_bank_name"
                                       wire:model.defer="current_bank_name"
                                       placeholder="{{ __('admin::home.bank_name') }}">
                                @error('current_bank_name') <span class="d-block text-danger mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="current_beneficiary_full_name" class="form-label text-default">{{ __('admin::home.beneficiary_full_name') }}</label>
                                <input type="text"
                                       class="form-control @error('current_beneficiary_full_name') is-invalid @enderror"
                                       id="current_beneficiary_full_name"
                                       wire:model.defer="current_beneficiary_full_name"
                                       placeholder="{{ __('admin::home.beneficiary_full_name') }}">
                                @error('current_beneficiary_full_name') <span class="d-block text-danger mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="current_iban" class="form-label text-default">{{ __('admin::home.iban') }}</label>
                                <input type="text"
                                       class="form-control @error('current_iban') is-invalid @enderror"
                                       id="current_iban"
                                       wire:model.defer="current_iban"
                                       placeholder="{{ __('admin::home.iban') }}">
                                @error('current_iban') <span class="d-block text-danger mt-1">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="iban_file" class="form-label text-default">
                                    @lang('marketplace::home.Iban File') <span class="text-danger">*</span>
                                </label>
                                <input type="file"
                                       class="form-control @error('iban_file') is-invalid @enderror"
                                       id="iban_file"
                                       wire:model="iban_file"
                                       accept=".jpg,.jpeg,.png,.gif,.pdf,.xls,.xlsx,.csv">
                                <small class="form-text text-muted">@lang('admin::home.iban_file_max_size')</small>
                                @error('iban_file') <span class="d-block text-danger mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        @if($current_iban_file_path)
                            @php
                                $currentFileExtension = strtolower(pathinfo($merchantIban?->iban_file ?? '', PATHINFO_EXTENSION));
                            @endphp
                            <div class="mt-3">
                                @if(in_array($currentFileExtension, ['pdf', 'csv', 'xls', 'xlsx']))
                                    <a class="btn btn-outline-primary btn-sm"
                                       href="{{ $current_iban_file_path }}"
                                       target="_blank"
                                       rel="noopener">
                                        @lang('marketplace::home.show') @lang('marketplace::home.Iban File')
                                    </a>
                                @else
                                    <a href="{{ $current_iban_file_path }}" target="_blank" rel="noopener">
                                        <img src="{{ $current_iban_file_path }}"
                                             class="img-fluid rounded border"
                                             alt="@lang('marketplace::home.Iban File')"
                                             style="max-height: 180px">
                                    </a>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit"
                                    class="btn btn-success"
                                    wire:loading.attr="disabled"
                                    wire:target="updateInfo,iban_file">
                                <span wire:loading.class="d-none" wire:target="updateInfo">
                                    {{ __('admin::home.update') }}
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="updateInfo">
                                    @lang('admin::home.Wait')
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($merchantTempIban)
            <div class="col-12 col-xl-6">
                <div class="card custom-card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">@lang('admin::home.requested_iban_info')</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" wire:submit.prevent="acceptChanges">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label text-default">{{ __('admin::home.bank_name') }}</label>
                                    <input type="text" class="form-control bg-light" id="bank_name" value="{{ $bank_name }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="beneficiary_full_name" class="form-label text-default">{{ __('admin::home.beneficiary_full_name') }}</label>
                                    <input type="text" class="form-control bg-light" id="beneficiary_full_name" value="{{ $beneficiary_full_name }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="iban" class="form-label text-default">{{ __('admin::home.iban') }}</label>
                                    <input type="text" class="form-control bg-light" id="iban" value="{{ $iban }}" readonly>
                                </div>
                            </div>

                            @if($iban_file_path)
                                @php
                                    $requestedFileExtension = strtolower(pathinfo($merchantTempIban?->iban_file ?? '', PATHINFO_EXTENSION));
                                @endphp
                                <div class="mt-3">
                                    @if(in_array($requestedFileExtension, ['pdf', 'csv', 'xls', 'xlsx']))
                                        <a class="btn btn-outline-primary btn-sm"
                                           href="{{ $iban_file_path }}"
                                           target="_blank"
                                           rel="noopener">
                                            @lang('marketplace::home.show') @lang('marketplace::home.Iban File')
                                        </a>
                                    @else
                                        <a href="{{ $iban_file_path }}" target="_blank" rel="noopener">
                                            <img src="{{ $iban_file_path }}"
                                                 class="img-fluid rounded border"
                                                 alt="@lang('marketplace::home.Iban File')"
                                                 style="max-height: 180px">
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit"
                                        class="btn btn-success"
                                        wire:loading.attr="disabled"
                                        wire:target="acceptChanges">
                                    <span wire:loading.class="d-none" wire:target="acceptChanges">
                                        {{ __('admin::home.accept') }}
                                    </span>
                                    <span class="d-none" wire:loading.class.remove="d-none" wire:target="acceptChanges">
                                        @lang('admin::home.Wait')
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
