<?php

namespace Application\Admin\Livewire\Offer\Modal;

use App\Domain\AppBase\Actions\Offer\FetchSingleOfferAction;
use App\Domain\AppBase\Actions\Offer\UpdateOfferAction;
use App\Domain\AppBase\Models\Offer;
use Domain\SalesAgent\Models\SalesAgent;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public string $type = 'percent';
    public string $type_discount = "order";
    public string $offerId = "";

    public string $name;
    public string $note;
    public int $usages_left;
    public float $min_order_value;
    public float|null $max_discount;
    public float|null $discount_value;
    public $expired_at;
    public Offer $offer;
    public $limit_per_user;
    public $is_custom = false;
    public $period;
    public $page = "update";



    protected $listeners = ['updateOffer','selectSalesAgent'];

    public function updateOffer($id, FetchSingleOfferAction $fetchSingleOfferAction): void
    {

        $this->offer = $fetchSingleOfferAction->handle($id);
        if ($this->offer) {
            $this->offerId = $this->offer->id;
            $this->name = $this->offer->name;
            $this->usages_left = $this->offer->usages_left;
            $this->limit_per_user = $this->offer->limit_per_user;
            $this->status = $this->offer->status->value;
            $this->type = $this->offer->type;
            $this->type_discount = $this->offer?->type_discount;
            $this->discount_value = $this->offer->discount_value;
            $this->max_discount = $this->offer->max_discount;
            $this->min_order_value = $this->offer->min_order_value;
            $this->period = $this->offer->period;
            $this->is_custom = $this->offer->is_custom;
            $this->note = $this->offer->note;
            $this->expired_at = $this->offer->expired_at->format('Y-m-d');
            $this->emit('openModal', '#updateOffer');
        }
    }


    protected function rules(): array
    {
        return [
            'name' => 'required|unique:offers,name,'.$this->offer->id,
            'usages_left' => 'required',
            'limit_per_user' => 'required',
            'min_order_value' => 'required',
            'max_discount' => 'required',
            'discount_value' => 'required',
            'period' => 'required',
            'type' => 'required|string|in:flat,percent',
            'expired_at' => 'required',
            'status' => 'required|string|in:active,inactive',
            'is_custom' => 'required',
            'note' => 'required',
            'type_discount' => 'required|string|in:order,delivery,total',

        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateOfferAction $updateOfferAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateOfferAction->handle($this->offer->id ,$data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#updateOffer');
        $this->alert('success', 'Coupon Code Updated Successfully');
        DB::commit();
    }

    public function customHandle(){
        $this->is_custom = !$this->is_custom;
    }

  

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.offer.modal.update');
    }
}
