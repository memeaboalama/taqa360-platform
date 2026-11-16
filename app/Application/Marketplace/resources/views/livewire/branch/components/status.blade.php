<div class="prism-toggle d-flex align-items-center gap-2">
    <label for="active"
           class="form-label text-success fw-bolder mb-0">{{ __('marketplace::home.status') }}</label>
    <div class="custom-toggle-switch d-flex align-items-center m-0">
        <input id="active"
               type="checkbox" {{ $status  ? 'checked' : '' }}>
        <label for="active" class="label-success"
               wire:click="updateStatus('{{ $status  ? false : true }}')"></label>
    </div>
    @error('status') <span class="d-inline-block text-info">{{ $message }}</span> @enderror
</div>
