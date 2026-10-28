<div class="prism-toggle">
    <label for="active"
           class="form-label text-success fw-bolder">{{ __('marketplace::home.status') }}</label>
    <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
        <input id="active"
               type="checkbox" {{ $status  ? 'checked' : '' }}>
        <label for="active" class="label-success"
               wire:click="updateStatus('{{ $status  ? false : true }}')"></label>
    </div>
    @error('status') <span class="d-inline-block text-info">{{ $message }}</span> @enderror
</div>
