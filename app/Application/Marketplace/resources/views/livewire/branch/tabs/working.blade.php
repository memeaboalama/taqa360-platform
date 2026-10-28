<div>
    <form wire:submit.prevent="store">
        @foreach($this->items as $day => $dayData)
            <div class="row mb-3 mx-2">
                <input type="hidden"  wire:model.defer="items.{{ $day }}.id">
                <div class="col-xl-4">
                    <label for="category-active"
                           class="form-label text-black">{{ $day }}</label>
                    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                        <input id="{{$day}}-active"
                               type="checkbox" {{ isset($dayData['status']) && $dayData['status'] == 'active' ? 'checked' : '' }}>
                        <label for="{{$day}}-active" class="label-success"
                               wire:click="updateDayStatus('{{ $day }}','{{ isset($dayData['status']) && $dayData['status'] == 'active' ? 'inactive' : 'active' }}')"></label>
                    </div>
                </div>
                <div class="col-xl-8 text-end">
                    <button type="button" wire:click.prevent="addItem('{{ $day }}')" class="btn btn-primary btn-sm ">@lang('marketplace::home.Add Time')</button>
                </div>
            </div>
            <div class="row gy-3 mt-2">
                <div class="col-md-8">
                    <div class="row gy-3 justify-content-center">
                        <div class="col-xl-3">
                            <label for="title" class="form-label text-black">@lang('marketplace::home.From')</label>
                        </div>
                        <div class="col-xl-3">
                            <label for="title" class="form-label text-black">@lang('marketplace::home.To')</label>
                        </div>
                        <div class="col-xl-3">
                            <label for="status" class="form-label text-black">@lang('marketplace::home.status')</label>
                        </div>
                        <div class="col-xl-2">
                            <label for="status" class="form-label text-black">@lang('marketplace::home.actions')</label>
                        </div>

                    </div>
                </div>
            </div>
            @if(isset($dayData['hours']))
                @foreach($dayData['hours'] as $key => $value)
                    <div class="row gy-3 mt-2">
                        <div class="col-md-8">
                            <div class="row gy-3 justify-content-center">
                                <div class="col-xl-3">
                                    <input class="form-control @error('items.' . $day . '.hours.' . $key .'.from') is-invalid @enderror bg-gray-200 " type="time" value="23:59" wire:model.defer="items.{{ $day }}.hours.{{$key}}.from">
                                    @error('items.' . $day . '.hours.' . $key .'.from')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-xl-3">
                                    <input class="form-control @error('items.' . $day . '.hours.' . $key .'.to') is-invalid @enderror  bg-gray-200" type="time" value="23:59" wire:model.defer="items.{{ $day }}.hours.{{$key}}.to">
                                    @error('items.' . $day . '.hours.' . $key .'.to')
                                    <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-xl-3">
                                    <select
                                        class="form-control @error('items.' . $day . '.hours.' . $key .'.status') is-invalid @enderror  bg-gray-200" wire:model.defer="items.{{ $day }}.hours.{{$key}}.status">
                                        <option value="">---@lang('marketplace::home.Select')---</option>
                                        <option value="active" {{ isset($value['status']) && $value['status'] == 'active' || (empty($value['status'])) ? 'selected' : '' }}>@lang('marketplace::home.Active')</option>
                                        <option value="inactive" {{ isset($value['status']) && $value['status'] == 'inactive' ? 'selected' : '' }}>@lang('marketplace::home.Inactive')</option>
                                    </select>
                                    @error('items.' . $day . '.hours.' . $key .'.status')
                                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-2">
                                    <button type="button" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill mt-1"
                                            wire:click="confirmDelete('{{ $day }}', {{ $dayData['id'] ?? 'null' }}, {{ $key }}, {{ $value['id'] ?? 'null' }})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <hr>
        @endforeach

            <div class="col-xl-4 d-grid mt-4 text-end">
                <button type="submit" class="btn btn-success">
                    <span class="indicator-label" wire:loading.class="d-none">
                        @lang('marketplace::home.Save')
                    </span>
                    <span class="d-none" wire:loading.class.remove="d-none">
                        @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>

            </div>
    </form>
    <!-- Confirm Delete Time Modal -->
    <div class="modal fade" id="deleteHourModal" tabindex="-1" aria-labelledby="deleteHourModalLabel"
         data-bs-keyboard="false" data-bs-backdrop="static" wire:ignore.self aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="deleteHourModalLabel">@lang('marketplace::messages.WORKING_DELETE_TITLE')</h6>
                </div>
                <div class="modal-body">
                    @lang('marketplace::messages.WORKING_DELETE_BODY')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:loading.attr="disabled">@lang('marketplace::messages.WORKING_DELETE_CANCEL')</button>
                    <button type="button" class="btn btn-danger"
                            wire:click="confirmDeleteHour" wire:loading.attr="disabled">
                        <span wire:loading.class="d-none" wire:target="confirmDeleteHour">@lang('marketplace::messages.WORKING_DELETE_CONFIRM')</span>
                        <span class="d-none" wire:loading.class.remove="d-none" wire:target="confirmDeleteHour">
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span> @lang('marketplace::messages.WORKING_DELETING')
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('deleteHourModal');
            if (!modalEl) return;
            modalEl.addEventListener('hide.bs.modal', function (e) {
                const confirmBtn = modalEl.querySelector('[wire\\:click="confirmDeleteHour"]');
                if (confirmBtn && confirmBtn.disabled) {
                    e.preventDefault();
                }
            });
        });
    </script>
</div>
