<div class="card custom-card">
    <div class="card-header justify-content-between">
        <h5 class="card-title mb-0">@lang('admin::home.driver_custody_balances')</h5>
        <input type="search" class="form-control" style="max-width: 320px"
               wire:model.debounce.500ms="search"
               placeholder="@lang('admin::home.search_driver')">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>@lang('admin::home.driver_name')</th>
                        <th>@lang('admin::home.current_balance')</th>
                        <th>@lang('admin::home.pending_settlement')</th>
                        <th>@lang('admin::home.custody_actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $driver)
                        <tr>
                            <td>
                                {{ $driver->name }}
                                <span class="text-muted d-block">{{ $driver->phone }}</span>
                            </td>
                            <td class="fw-semibold">{{ number_format($driver->custody_balance, 2) }} SAR</td>
                            <td>{{ number_format($driver->pending_settlement_amount, 2) }} SAR</td>
                            <td>
                                <a class="btn btn-sm btn-primary"
                                   href="{{ route('admin:driver-custody.show', $driver->id) }}">
                                    @lang('admin::home.open_statement')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">@lang('admin::home.no_drivers_found')</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $drivers->links() }}
    </div>
</div>
