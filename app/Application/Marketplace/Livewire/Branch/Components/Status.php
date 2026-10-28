<?php

namespace Application\Marketplace\Livewire\Branch\Components;

use App\Domain\MarketPlace\Actions\Dashboard\Branch\FetchSingleBranchAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\UpdateBranchAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Status extends Component
{
    use LivewireAlert;
    public $status = true;
    public $branch_id;
    public $merchant_id;
    protected $listeners = ['updateStatus'];

    public function mount($branch_id, FetchSingleBranchAction $fetchBranchAction): void
    {
        $this->merchant_id=resolve('_InitialMerchant');
        $branch = $fetchBranchAction->handle($branch_id, resolve('_InitialMerchant'));
        if ($branch) {
            $this->branch_id = $branch->id;
            $this->merchant_id = $branch->merchant_id;
            $this->status = $branch->status;
        } else {
            abort(404);
        }
    }

    public function updateStatus(UpdateBranchAction $updateBranchAction, $status): void
    {
        $this->status = $status ? 1 : 0;
        DB::beginTransaction();
        $updateBranchAction->handle($this->branch_id, ['status' => $this->status]);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.branch.components.status');
    }
}
