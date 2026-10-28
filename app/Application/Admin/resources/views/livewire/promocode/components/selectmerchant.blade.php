<div class="prism-toggle">
    <label for="active"
           class="form-label text-success fw-bolder">{{ __('admin::home.Enable Coupon') }}</label>
    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
        <input id="active"
               type="checkbox" {{ $promo_status  ? 'checked' : '' }}>
        <label for="active" class="label-success" wire:click.prevent="updateStatus({{ $promo_status ? 0 : 1 }}, {{ $row->id }})" wire:ignore.self></label>
    </div>
    @error('status') <span class="d-inline-block text-info">{{ $message }}</span> @enderror

</div>
