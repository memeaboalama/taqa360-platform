<?php

namespace Application\Admin\Livewire\Order\Table;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Domain\MarketPlace\Actions\Order\OrderAction;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Application\Marketplace\Enums\MerchantOrderMarks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Domain\AppBase\Models\Payment;

use Domain\Driver\Models\Driver;
use App\Domain\ElmApp\Actions\Order\ManuallyAssignDriverAction;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;

class Read extends Component
{
    use LivewireAlert;

    public $order;
    public $orderItems;
    public $orderUser;
    public $orderServices;
    public $address;
    public $mark = MerchantOrderMarks::WHITE; //'white';
    public $admin_note;

    public $driver;
    public $drivers = [];
    public $selected_driver_id;
    public $payment;
    public Collection $marks;
    protected $listeners = ['refreshDatatable' => '$refresh', 'updateMark', 'updateNote'];
    public function mount($order, $orderItems, $orderUser, $address, $driver, $payment)
    {
        //        dd($order->branch);
        $this->order = $order;
        $this->orderItems = $orderItems;
        $this->orderUser = $orderUser;
        $this->address = $address;
        if (!$this->address && $order->addressInOrder && !is_null($order->addressInOrder)) {
            if (is_string($order->addressInOrder)) {
                $this->order->addressInOrder = json_decode($order->addressInOrder);
                // } else if (is_array($order->addressInOrder) || is_object($order->addressInOrder)) {
            } else if (is_array($order->addressInOrder)) {
                $this->order->addressInOrder = (object)$order->addressInOrder; //= object;
                foreach ($order->addressInOrder as $k => $v) //{
                    // $annotation = $this->doDeserialize($v, $nestedClass, $context);
                    $this->order->addressInOrder->{$k} = $v;
                // $this->order->addressInOrder = (object)$order->addressInOrder;
            } else if (is_object($order->addressInOrder)) {
                $this->order->addressInOrder = (object)$order->addressInOrder;
                // $this->order->addressInOrder = json_decode($order->addressInOrder);
            } else {

                $this->order->addressInOrder = (object)$order->addressInOrder;
            }
        } else {
            $this->order->addressInOrder = null;
        }
        $this->driver = $driver;
        $this->drivers = Driver::all();
        $this->selected_driver_id = $order->driver_id;
        $this->payment = $payment ?? $order->payment()
            ->latest('id')
            ->first()
            ?? Payment::where('payable_id', $order?->id)
                ->where(function ($q) use ($order) {
                    $q->where('payable_type', $order?->getMorphClass())
                      ->orWhere('payable_type', 'App\\Domain\\MarketPlace\\Models\\MerchantOrder');
                })
                ->latest('id')
                ->first()
            ?? $order->payment;
        $this->mark = $order->mark;
        $this->admin_note = $order->admin_note;
        $this->marks = collect(MerchantOrderMarks::cases());
    }

    public function hydrate(): void
    {
        if (is_string($this->mark)) {
            $this->mark = MerchantOrderMarks::tryFrom($this->mark) ?? MerchantOrderMarks::WHITE;
        }
        $this->drivers = Driver::all();
        $this->marks = collect(MerchantOrderMarks::cases());
    }

    public function assignDriver(): void
    {
        if (!$this->selected_driver_id) {
            $this->alert('warning', __('admin::home.Please Select Driver'));
            return;
        }

        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $logHistory = app(LogOrderHistoryAction::class);
        $assignAction = new ManuallyAssignDriverAction($orderRepo, $logHistory);

        $driverModel = Driver::find($this->selected_driver_id);
        if ($driverModel) {
            $user = auth()->user() ?? $this->orderUser;
            $assignAction->handle($this->order->id, [
                'driver_id' => $driverModel->id,
                'commission_from_driver' => $driverModel->taken_commission ?? 0,
            ], $user);

            $this->order->refresh();
            $this->driver = $driverModel;
            $this->emit('refreshDatatable');
            $this->alert('success', __('admin::home.driver_assigned_successfully'));
        }
    }
    public function render()
    {
        if (is_string($this->mark)) {
            $this->mark = MerchantOrderMarks::tryFrom($this->mark) ?? MerchantOrderMarks::WHITE;
        }
        $this->marks = collect(MerchantOrderMarks::cases());

        return view('admin::livewire.order.table.read', [
            'order' => $this->order,
            'orderItems' => $this->orderItems,
            'orderUser' => $this->orderUser,
            'address' => $this->address,
            'driver' => $this->driver,
            'payment' => $this->payment,
        ]);
    }
    public function updateNote(): void
    {
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $orderAction = new OrderAction($orderRepo);
        DB::beginTransaction();
        $orderAction->updateAdminNote($this->order->id, $this->admin_note);
        DB::commit();
        $this->alert('success', __('admin::home.data_updated_successfully'));
    }
    public function updateMark(): void
    {
        $orderRepo = new MerchantOrderRepository(new MerchantOrder());
        $orderAction = new OrderAction($orderRepo);
        DB::beginTransaction();
        $orderAction->updateMark($this->order->id, $this->mark);
        $this->mark = MerchantOrderMarks::from($this->mark);
        $this->marks = collect(MerchantOrderMarks::cases());
        $this->emit('refresh');
        DB::commit();
    }
}
