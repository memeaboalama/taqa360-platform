<div class="row g-4">
    <div class="col-lg-7">
        <div class="card custom-card h-100">
            <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">@lang('admin::educators.tabs.services_skills')</h5>
            </div>
            <div class="card-body p-4">
                @if($services->isNotEmpty())
                    <div class="d-flex flex-column gap-3">
                        @foreach($services as $service)
                        <div class="card shadow-none border">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="flex-shrink-0">
                                        @if($service->is_custom)
                                            <div class="timeline-icon bg-warning-subtle text-warning">
                                                <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 011.087-5.456L10.5 9.75M9.813 15.904L12 18.75l2.187-2.846a4.5 4.5 0 00-1.087-5.456L13.5 9.75m-3.687 6.154a4.5 4.5 0 106.364-6.364m-6.364 6.364L6 18.75" /></svg>
                                            </div>
                                        @else
                                            <div class="timeline-icon bg-primary-subtle text-primary">
                                                <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.07a2.25 2.25 0 01-2.25 2.25h-13.5a2.25 2.25 0 01-2.25-2.25v-4.07m16.5-8.25a2.25 2.25 0 00-2.25-2.25h-13.5a2.25 2.25 0 00-2.25 2.25m16.5 0v4.07m-16.5-4.07v4.07m16.5 0v3.75m-16.5-3.75v3.75m16.5 0a2.25 2.25 0 01-2.25 2.25h-13.5a2.25 2.25 0 01-2.25-2.25m16.5 0m-16.5 0" /></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $service->is_custom ? $service->custom_name : $service->approvedService?->name }}</h6>
                                        <p class="small text-muted mb-0">{{ $service->custom_description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-5">@lang('admin::educators.no_services_added')</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card custom-card h-100">
             <div class="card-header bg-white">
                <h5 class="fw-bold mb-0">@lang('admin::educators.skills')</h5>
            </div>
            <div class="card-body p-4">
                @if($skills->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($skills as $skill)
                            <span class="badge rounded-pill bg-light text-dark border p-2 fw-normal">
                                {{ $skill->is_custom ? $skill->custom_name : $skill->approvedSkill?->name }}
                                <span class="vr mx-1"></span>
                                <span class="text-primary fw-bold">{{ $this->getLevelLabel($skill->level) }}</span>
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-5">@lang('admin::educators.no_skills_added')</p>
                @endif
            </div>
        </div>
    </div>
</div>
