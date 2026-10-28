<div class="prism-toggle">
    <label for="active"
           class="form-label {{ $promo_status ? 'text-danger' : 'text-success' }} fw-bolder">
        {{ $promo_status ? __('admin::home.Deactivate Coupon') : __('admin::home.Enable Coupon') }}
    </label>
    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
        <input id="active"
               type="checkbox" {{ $promo_status  ? 'checked' : '' }}>
        <label for="active" class="label-success" wire:click.prevent="updateStatus({{ $promo_status ? 0 : 1 }}, {{ $row->id }})" wire:ignore.self></label>
    </div>
    @error('status') <span class="d-inline-block text-info">{{ $message }}</span> @enderror

</div>
