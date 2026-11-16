<div class="prism-toggle">
    @MerchantHasRole(['edit info_branches'])
    <div class="custom-toggle-switch d-flex align-items-center">
        <input id="active-{{ $row->id }}"
               type="checkbox" {{ $row->status  ? 'checked' : '' }}>
        <label for="active-{{ $row->id }}" class="label-success m-0"
               wire:click="updateStatus({{ $row->status ? 0 : 1 }}, {{ $row->id }})"></label>
    </div>
    @error('status') <span class="d-inline-block text-info">{{ $message }}</span> @enderror
    @endMerchantHasRole

</div>
