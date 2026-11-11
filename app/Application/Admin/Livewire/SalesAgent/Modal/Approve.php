<?php

namespace Application\Admin\Livewire\SalesAgent\Modal;

use App\Domain\Admin\Actions\Dashboard\SalesAgent\ApproveSalesAgentAction;
use App\Domain\Admin\Actions\Dashboard\SalesAgent\FetchSingleSalesAgentAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Approve extends Component
{
    use LivewireAlert;

    public $sales_agent_id;
    protected $listeners = ['approveSalesAgent'];

    public function approveSalesAgent($id, FetchSingleSalesAgentAction $fetchSingleDriverAction): void
    {
        $sales_agent = $fetchSingleDriverAction->handle($id);
        if ($sales_agent) {
            $this->sales_agent_id = $sales_agent->id;
            $this->emit('openModal', '#approveSalesAgent');
        }
    }

    public function accept(ApproveSalesAgentAction $approveSalesAgentAction): void
    {
        if (!$this->sales_agent_id) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $approved = DB::transaction(fn () => $approveSalesAgentAction->handle((int) $this->sales_agent_id));
        if (!$approved) {
            $this->alert('error', __('admin::home.something_went_wrong'));
            return;
        }

        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#approveSalesAgent');
        $this->alert('success', __('admin::home.data_updated_successfully'));
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.salesagent.modal.approve');
    }
}
