<div>
    <div class="modal fade" id="sendEducatorNotification" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1"
         aria-labelledby="sendEducatorNotificationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="sendEducatorNotificationLabel">@lang('admin::educators.send_notification')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            wire:loading.attr="disabled" wire:target="send" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="send">
                    <div class="modal-body">
                        <div class="row gy-3">
                            <div class="col-xl-12">
                                <label for="educatorNotifTarget" class="form-label text-black">@lang('admin::educators.send_to')</label>
                                <input type="text" id="educatorNotifTarget" class="form-control" value="{{ $educator->name }}" disabled>
                            </div>
                            <div class="col-xl-12">
                                <label for="educatorNotifTitle" class="form-label text-black">@lang('admin::home.Title') <span class="text-danger">*</span></label>
                                <input type="text" id="educatorNotifTitle" class="form-control @error('title') is-invalid @enderror"
                                       placeholder="@lang('admin::home.Title')" wire:model.defer="title">
                                @error('title') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-xl-12">
                                <label for="educatorNotifBody" class="form-label text-black">@lang('admin::home.message') <span class="text-danger">*</span></label>
                                <textarea id="educatorNotifBody" class="form-control @error('body') is-invalid @enderror text-black"
                                          rows="5" placeholder="@lang('admin::home.message')" wire:model.defer="body"></textarea>
                                @error('body') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="send">@lang('admin::home.cancel')</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="send">
                            <span class="indicator-label" wire:loading.class="d-none" wire:target="send">@lang('admin::home.send')</span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="send">@lang('admin::home.wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>