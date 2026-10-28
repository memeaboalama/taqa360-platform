<?php

namespace Application\Admin\Livewire\Company\Tabs\Orders;

use App\Domain\MarketPlace\Actions\Dashboard\Order\AssignEmployeeAction;
use App\Domain\Provider\Actions\Dashboard\Order\FetchSingleOrderAction;
use App\Domain\Provider\Actions\Dashboard\Provider\AssignProviderAction;
use App\Domain\Provider\Models\Provider;
use App\Support\Enum\StatusEnum;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Assign extends Component
{
    use LivewireAlert;

    protected $listeners = ['assignProvider'];
    public $order;
    public $orderUser;
    public $providers = [];
    public $orderServices = [];
    public $address;
    public mixed $company_id;
    public mixed $order_id;
    public mixed $provider_sub_services;
    public mixed $towingOrder;
    /**
     * @var mixed|null
     */
    public mixed $provider;

    public function mount(int $order_id): void
    {
        $this->order_id = $order_id;
    }

    public function assignProvider(FetchSingleOrderAction $fetchSingleOrderAction): void
    {
        $this->order = $fetchSingleOrderAction->handle($this->order_id);
        abort_if(!$this->order, 404);
        if ($this->order) {
            $this->provider = $this->order->proivder;
            $this->providers = Provider::where('status', StatusEnum::ACTIVE)->get();
            $this->emit('openModal', '#assignProvider');
        }
    }


    protected function rules(): array
    {
        return [
            'provider' => 'required|exists:providers,id',
        ];
    }

    public function store(AssignProviderAction $assignProviderAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $res = $assignProviderAction->handle($this->order->id, $data);
        $this->emit('closeModal', '#assignProvider');
        $this->emit('refresh_provider', $res->id);
        $this->emit('refreshDatatable');
        $this->alert('success', 'Provider Assigned Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.company.tabs.orders.assign');
    }


}
