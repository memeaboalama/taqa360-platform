<div>
    {{-- <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-0">ملف المعلم</h1>
                <p class="text-muted mb-0">مراجعة وتعديل بيانات المعلم/المزود.</p>
            </div>
            <a href="" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/></svg>
                العودة للمعلمين
            </a>
        </div> --}}

        <div class="card custom-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    <img src="{{ $educator->profile_image_url }}" alt="{{ $educator->name }}" class="rounded-circle profile-avatar">

                    <div class="flex-grow-1 text-center text-md-start">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                            <h2 class="h4 fw-bold mb-0">{{ $educator->name }}</h2>
                            <span class="badge {{ $educator->status->color() }}">{{ $educator->status->trans() }}</span>
                        </div>
                        <p class="text-muted mb-2">{{ $educator->profile?->specialization ?? __('admin::educators.no_specialization') }}</p>
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3 text-muted small">
                            <div class="d-flex align-items-center gap-1">
                                <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                <span>{{ $educator->profile?->city?->title ?? __('admin::educators.not_specified') }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                <span>{{ $educator->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-stretch align-items-md-end gap-2 mt-3 mt-md-0" style="min-width: 180px;">
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-primary dropdown-toggle fw-bold" data-bs-toggle="dropdown" aria-expanded="false">
                                @lang('admin::educators.actions')
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button wire:click="changeStatus('active')" class="dropdown-item">@lang('admin::educators.approve_account')</button></li>
                                <li><button wire:click="changeStatus('pending')" class="dropdown-item">@lang('admin::educators.suspend_account')</button></li>
                                <li><button wire:click="openRejectModal" class="dropdown-item text-danger">@lang('admin::educators.reject_account')</button></li>
                            </ul>
                        </div>
                        <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#sendEducatorNotification">@lang('admin::educators.send_notification')</button>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills nav-fill bg-white p-2 rounded-3 shadow-sm mb-4" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab === 'overview' ? 'active' : '' }}" wire:click="$set('activeTab', 'overview')" type="button">@lang('admin::educators.tabs.overview')</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab === 'academic' ? 'active' : '' }}" wire:click="$set('activeTab', 'academic')" type="button">@lang('admin::educators.tabs.academics')</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab === 'services' ? 'active' : '' }}" wire:click="$set('activeTab', 'services')" type="button">@lang('admin::educators.tabs.services_skills')</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab === 'documents' ? 'active' : '' }}" wire:click="$set('activeTab', 'documents')" type="button">@lang('admin::educators.tabs.documents')</button></li>
        </ul>
        {{-- <div>
            @if ($activeTab === 'overview')
                <livewire:admin::educator.profile.tabs.overview :educator="$educator" wire:key="tab-overview" />
            @elseif ($activeTab === 'services')
                <livewire:admin::educator.profile.tabs.services :educator="$educator" wire:key="tab-services" />
            @elseif ($activeTab === 'portfolio')
                <livewire:admin::educator.profile.tabs.portfolio :educator="$educator" wire:key="tab-portfolio" />
            @elseif ($activeTab === 'achievements')
                <livewire:admin::educator.profile.tabs.achievements :educator="$educator" wire:key="tab-achievements" />
            @elseif ($activeTab === 'reviews')
                <livewire:admin::educator.profile.tabs.reviews :educator="$educator" wire:key="tab-reviews" />
            @endif
        </div> --}}
        <div>
            @if ($activeTab === 'overview')
                <livewire:admin::educator.profile.tabs.overview :educator="$educator" :key="'tab-overview-'.$educator->id" />
            @elseif ($activeTab === 'academic')
                <livewire:admin::educator.profile.tabs.academic :educator="$educator" :key="'tab-academic-'.$educator->id" />
            @elseif ($activeTab === 'services')
                <livewire:admin::educator.profile.tabs.services :educator="$educator" :key="'tab-services-'.$educator->id" />
            @elseif ($activeTab === 'documents')
                <livewire:admin::educator.profile.tabs.document :educator="$educator" :key="'tab-documents-'.$educator->id" />
            @endif
        </div>
        <livewire:admin::educator.profile.components.send-notification :educator="$educator" />

        <div class="modal fade" id="rejectEducatorModal" wire:ignore.self
             data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1"
             aria-labelledby="rejectEducatorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="rejectEducatorModalLabel">@lang('admin::educators.reject_account')</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                wire:loading.attr="disabled" wire:target="rejectAccount" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="rejectAccount">
                        <div class="modal-body">
                            <div class="row gy-3">
                                <div class="col-xl-12">
                                    <label for="educatorRejectionReason" class="form-label text-black">@lang('admin::educators.rejection_reason') <span class="text-danger">*</span></label>
                                    <textarea id="educatorRejectionReason" class="form-control @error('rejection_reason') is-invalid @enderror text-black"
                                              rows="4" placeholder="@lang('admin::educators.rejection_reason_placeholder')" wire:model.defer="rejection_reason"></textarea>
                                    @error('rejection_reason') <span class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"
                                    wire:loading.attr="disabled" wire:target="rejectAccount">@lang('admin::home.cancel')</button>
                            <button type="submit" class="btn btn-danger" wire:loading.attr="disabled" wire:target="rejectAccount">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="rejectAccount">@lang('admin::educators.reject')</span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="rejectAccount">@lang('admin::home.wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
