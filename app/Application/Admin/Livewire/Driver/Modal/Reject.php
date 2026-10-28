<?php

namespace Application\Admin\Livewire\Driver\Modal;

use App\Domain\Admin\Actions\Dashboard\Driver\FetchSingleDriverAction;
use App\Domain\Admin\Actions\Dashboard\Driver\RejectDriverAction;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Reject extends Component
{
    use LivewireAlert;

    public $driver_id;
    public ?string $rejection_reason = null;
    protected $listeners = ['rejectDriver'];

    public function rejectDriver($id, FetchSingleDriverAction $fetchSingleDriverAction): void
    {
        $driver = $fetchSingleDriverAction->handle($id);
        if ($driver) {
            $this->driver_id = $driver->id;
            $this->rejection_reason = null;
            $this->resetValidation('rejection_reason');
            $this->emit('openModal', '#rejectDriver');
        }
    }

    public function closeRejectModal(): void
    {
        $this->rejection_reason = null;
        $this->resetValidation('rejection_reason');
        $this->emit('closeModal', '#rejectDriver');
    }

    public function reject(RejectDriverAction $rejectDriverAction, FetchSingleDriverAction $fetchSingleDriverAction): void
    {
        $driver = $fetchSingleDriverAction->handle($this->driver_id);
        if (!$driver) {
            $this->emit('closeModal', '#rejectDriver');
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        if ((int) $driver->is_complete === 1
            || ($driver->getAttributes()['status'] ?? null) === StatusEnum::REJECTED->value) {
            $this->alert('error', __('admin::home.reject_not_allowed'));
            return;
        }

        $this->validate(
            ['rejection_reason' => 'required|string|max:500'],
            ['rejection_reason.required' => __('admin::home.rejection_reason_required')]
        );

        DB::beginTransaction();
        try {
            $result = $rejectDriverAction->handle($this->driver_id, $this->rejection_reason);
            if (!$result) {
                DB::rollBack();
                $this->alert('error', __('admin::home.reject_not_allowed'));
                return;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->rejection_reason = null;
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#rejectDriver');
        $this->alert('success', __('admin::home.driver_rejected_successfully'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.modal.reject');
    }
}
