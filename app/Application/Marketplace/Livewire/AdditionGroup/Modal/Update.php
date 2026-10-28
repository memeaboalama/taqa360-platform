<?php

namespace Application\Marketplace\Livewire\AdditionGroup\Modal;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Domain\MarketPlace\Actions\Dashboard\AdditionGroup\UpdateAdditionGroupAction;
use App\Domain\MarketPlace\Actions\Dashboard\AdditionGroup\FetchSingleAdditionGroupAction;

class Update extends Component
{
    use LivewireAlert;


    public  $status = 'active',  $title = ['en' => null,  'ar' => null], $min_value, $max_value, $order, $multi,$model_id;

    public $options = [], $choices;
    public $merchantId;
    protected $listeners = ['updateAdditionGroup'];
    public function mount()
    {
        $this->merchantId = resolve('_InitialMerchant');
        $this->choices = \Domain\MarketPlace\Models\Choice::where(['status' =>  'active', 'merchant_id' => $this->merchantId])->get();
    }

    protected function rules(): array
    {
        return [
            'title.en' => ['required'],
            'title.ar' => ['required'],
            'multi' => 'required|in:0,1',
            'status' => 'required|string|in:active,inactive',
            'min_value' => 'numeric|lte:' . count($this->options),
            'max_value' => 'required|numeric|lte:' . count($this->options),
            'order' => 'required|numeric',
            'options' => 'array|required',
            'options.*.choice_id' => 'required|numeric|distinct',
            'options.*.price' => 'required|numeric',
            'options.*.order' => 'required|numeric',

        ];
    }



    public function nickNames()
    {
        return [
            'title.ar' => trans('marketplace::home.Title'),
            'title.en' => trans('marketplace::home.Title'),
            'multi' => trans('marketplace::home.Show on app'),
            'order' => trans('marketplace::home.Priority'),
            'min_value' => trans('marketplace::home.min_customer_choices'),
            'max_value' => trans('marketplace::home.max_customer_choices'),
        ];
    }

    public function updateAdditionGroup($id, FetchSingleAdditionGroupAction $fetchSingleCategoryAction): void
    {

        $model = $fetchSingleCategoryAction->handle($id, $this->merchantId);
        if ($model) {
            $this->title = $model->getTranslations('title');
            $this->status = $model->status;
            $this->min_value = $model->min_value;
            $this->max_value = $model->max_value;
            $this->order = $model->order;
            $this->multi = $model->multi;
            $this->model_id = $model->id;
            $this->options = $model->choices->map(function ($choice) {
                return ['choice_id' => $choice->id, 'price' => $choice->pivot->price, 'order' => $choice->pivot->order];
            })->toArray();
            $this->emit('openModal', '#updateAdditionGroup');
        }
    }




    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateAdditionGroupAction $action): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $action->handle($this->model_id, $data);
        $this->emit('closeModal', '#updateAdditionGroup');
        $this->emit('refreshDatatable');
        $this->reset('title', 'status');
        $this->alert('success', __('marketplace::home.data_updated_successfully'));

        DB::commit();
    }


    // add new option

    public function addOption(): void
    {
        $this->options[] = ['choice_id' => null, 'price' => null, 'order' => null];
        $this->changeMinMax();
    }

    // remove option

    public function deleteOption($index): void
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
        $this->changeMinMax();
    }

    public function changeMinMax() {
        $this->min_value = $this->multi == 0 ? 1 : 0;
        $this->max_value = $this->multi == 0 ? 1 : count($this->options);
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.additiongroup.modal.update');
    }
}
