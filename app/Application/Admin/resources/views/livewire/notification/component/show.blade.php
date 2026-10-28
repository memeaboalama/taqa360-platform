<div>
    <div class="modal fade" id="showNotification" wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">{{ __('admin::home.view_notification') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($notification)
                        @php
                            $audienceMap = [
                                'App\Domain\User\Models\User' => __('admin::home.customers'),
                                'Domain\Driver\Models\Driver' => __('admin::home.Drivers'),
                                'Domain\MarketPlace\Models\MerchantEmployee' => __('admin::home.Merchants'),
                            ];
                            $audience = $audienceMap[$notification->device_type] ?? $notification->device_type;
                        @endphp
                        <div class="row gy-3">
                            <div class="col-xl-6">
                                <label class="form-label text-black fw-bold">{{ __('admin::home.Title') }}</label>
                                <p class="mb-0 text-black">{{ $notification->title }}</p>
                            </div>
                            <div class="col-xl-6">
                                <label class="form-label text-black fw-bold">{{ __('admin::home.send to') }}</label>
                                <p class="mb-0 text-black">{{ $audience }}</p>
                            </div>
                            <div class="col-xl-6">
                                <label class="form-label text-black fw-bold">{{ __('admin::home.Send Name') }}</label>
                                <p class="mb-0 text-black">{{ $notification->admin?->name ?? '-' }}</p>
                            </div>
                            <div class="col-xl-6">
                                <label class="form-label text-black fw-bold">{{ __('admin::home.Date') }}</label>
                                <p class="mb-0 text-black">{{ \Carbon\Carbon::parse($notification->created_at)->format('j F Y') }}</p>
                            </div>
                            <div class="col-xl-12">
                                <label class="form-label text-black fw-bold">{{ __('admin::home.message') }}</label>
                                <p class="mb-0 text-black">{{ $notification->body }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-center text-muted mb-0">{{ __('admin::home.not_found') }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">{{ __('admin::home.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
