<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1">{{ __('admin::lang.Customer Testimonials') }}</h4>
            <p class="text-muted mb-0">{{ __('admin::lang.Customer Testimonials Help') }}</p>
        </div>
    </div>

    <form wire:submit.prevent="save" class="border rounded p-3 mb-4">
        <h5 class="mb-3">
            {{ $editingId ? __('admin::lang.Edit Testimonial') : __('admin::lang.Add Testimonial') }}
        </h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="testimonial-name">{{ __('admin::lang.Customer Name') }}</label>
                <input id="testimonial-name" type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label" for="testimonial-position-ar">{{ __('admin::lang.Position Arabic') }}</label>
                <input id="testimonial-position-ar" type="text" class="form-control @error('positionAr') is-invalid @enderror" wire:model.defer="positionAr">
                @error('positionAr') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label" for="testimonial-position-en">{{ __('admin::lang.Position English') }}</label>
                <input id="testimonial-position-en" type="text" class="form-control @error('positionEn') is-invalid @enderror" wire:model.defer="positionEn">
                @error('positionEn') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="testimonial-content-ar">{{ __('admin::lang.Review Arabic') }}</label>
                <textarea id="testimonial-content-ar" rows="4" class="form-control @error('contentAr') is-invalid @enderror" wire:model.defer="contentAr"></textarea>
                @error('contentAr') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="testimonial-content-en">{{ __('admin::lang.Review English') }}</label>
                <textarea id="testimonial-content-en" rows="4" class="form-control @error('contentEn') is-invalid @enderror" wire:model.defer="contentEn"></textarea>
                @error('contentEn') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label" for="testimonial-sort-order">{{ __('admin::lang.Display Order') }}</label>
                <input id="testimonial-sort-order" type="number" min="0" max="65535" class="form-control @error('sortOrder') is-invalid @enderror" wire:model.defer="sortOrder">
                @error('sortOrder') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label" for="testimonial-image">{{ __('admin::lang.Image') }}</label>
                <input id="testimonial-image" type="file" accept=".jpg,.jpeg,.png,.webp" class="form-control @error('image') is-invalid @enderror" wire:model="image">
                <small class="text-muted">{{ __('admin::lang.Testimonial Image Help') }}</small>
                @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3 d-flex align-items-center">
                <div class="form-check">
                    <input id="testimonial-active" type="checkbox" class="form-check-input" wire:model="isActive">
                    <label class="form-check-label" for="testimonial-active">{{ __('admin::lang.Show on Website') }}</label>
                </div>
            </div>
        </div>

        @if ($image)
            <div class="mb-3">
                <img src="{{ $image->temporaryUrl() }}" alt="" style="max-width: 120px; max-height: 120px; object-fit: cover;">
            </div>
        @elseif ($existingImage)
            <div class="mb-3">
                <img src="{{ \Illuminate\Support\Str::startsWith($existingImage, ['http://', 'https://']) ? $existingImage : \Illuminate\Support\Facades\Storage::disk('fileDisk')->url($existingImage) }}" alt="" style="max-width: 120px; max-height: 120px; object-fit: cover;">
            </div>
        @endif

        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            {{ $editingId ? __('admin::lang.Save Changes') : __('admin::lang.Add Testimonial') }}
        </button>
        @if ($editingId)
            <button type="button" class="btn btn-light" wire:click="cancelEdit">{{ __('admin::lang.Cancel') }}</button>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>{{ __('admin::lang.Customer Name') }}</th>
                    <th>{{ __('admin::lang.Review Arabic') }}</th>
                    <th>{{ __('admin::lang.Display Order') }}</th>
                    <th>{{ __('admin::lang.Review Status') }}</th>
                    <th>{{ __('admin::lang.Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($testimonials as $testimonial)
                    <tr wire:key="customer-testimonial-{{ $testimonial->id }}">
                        <td>{{ $testimonial->name }}</td>
                        <td>{{ $testimonial->getTranslation('content', 'ar') }}</td>
                        <td>{{ $testimonial->sort_order }}</td>
                        <td>
                            <span class="badge {{ $testimonial->status === 'approved' ? ($testimonial->is_active ? 'bg-success' : 'bg-secondary') : ($testimonial->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ __('admin::lang.Testimonial Status ' . $testimonial->status) }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            @if ($testimonial->status !== 'approved')
                                <button type="button" class="btn btn-sm btn-success-light" wire:click="approve({{ $testimonial->id }})">
                                    {{ __('admin::lang.Approve Testimonial') }}
                                </button>
                            @endif
                            @if ($testimonial->status === 'pending')
                                <button type="button" class="btn btn-sm btn-warning-light" wire:click="reject({{ $testimonial->id }})">
                                    {{ __('admin::lang.Reject Testimonial') }}
                                </button>
                            @endif
                            <button type="button" class="btn btn-sm btn-primary-light" wire:click="edit({{ $testimonial->id }})">
                                {{ __('admin::lang.Edit') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-danger-light" wire:click="delete({{ $testimonial->id }})" onclick="return confirm('{{ __('admin::lang.Confirm Delete Testimonial') }}')">
                                {{ __('admin::lang.delete') }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">{{ __('admin::lang.No Testimonials') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
