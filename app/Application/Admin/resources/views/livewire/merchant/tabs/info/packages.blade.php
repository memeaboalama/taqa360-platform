<div wire:ignore.self>
    <div class="container-fluid py-3">
        <h5 class="fw-semibold mb-4">@lang('admin::home.Packages')</h5>

        <div class="row g-4">
            @foreach($packages as $package)
                @php($isSelected = (int) $package->id === (int) $selected_package)
                <div class="col-12 col-md-6 col-xl-4" wire:key="merchant-package-{{ $package->id }}">
                    <div class="card custom-card h-100 border {{ $isSelected ? 'border-primary shadow-sm' : 'border-light' }}">
                        <div class="card-body d-flex flex-column p-4">
                            <div class="mb-3">
                                <h6 class="card-title fw-semibold mb-0">{{ $package->title }}</h6>
                            </div>

                            <div class="d-flex align-items-baseline gap-2 mb-3">
                                <span class="badge rounded-pill bg-primary-transparent text-primary fs-6">
                                    {{ $package->commission }}%
                                </span>
                                <span class="text-muted small">@lang('admin::home.commission')</span>
                            </div>

                            @if($package->description)
                                <p class="text-muted mb-4">{{ $package->description }}</p>
                            @endif

                            <button wire:click="$emit('selectPackage', {{ $package->id }})"
                                    aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                                    class="btn {{ $isSelected ? 'btn-primary' : 'btn-outline-primary' }} w-100 mt-auto">
                                {{ $isSelected ? __('admin::home.Selected') : __('admin::home.Select') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
