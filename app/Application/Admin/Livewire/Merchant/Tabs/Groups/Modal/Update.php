<?php

namespace Application\Admin\Livewire\Merchant\Tabs\Groups\Modal;

use App\Domain\Admin\Actions\Dashboard\Menu\AdditonGroups\FetchSingleAdditionGroupAction;
use App\Domain\Admin\Actions\Dashboard\Menu\AdditonGroups\UpdateAdditionGroupAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;


    public  $status = 'active',
        $title = ['en' => null,  'ar' => null],
        $min_value,
        $max_value,
        $order,
        $multi,
        $model_id;

    public $options = [], $choices;
    public $merchantId;
    protected $listeners = ['updateAdditionGroup'];
    public function mount(int $merchantId)
    {
        $this->merchantId = $merchantId;
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
            'title.ar' => trans('admin::home.Title'),
            'title.en' => trans('admin::home.Title'),
            'multi' => trans('admin::home.Show on app'),
            'order' => trans('admin::home.Priority'),
            'min_value' => trans('admin::home.min_customer_choices'),
            'max_value' => trans('admin::home.max_customer_choices'),
        ];
    }

    public function updateAdditionGroup($id, FetchSingleAdditionGroupAction $fetchSingleAdditionGroupAction): void
    {
        $model = $fetchSingleAdditionGroupAction->handle($id, $this->merchantId);
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

    public function store(UpdateAdditionGroupAction $updateAdditionGroupAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        DB::beginTransaction();
        $updateAdditionGroupAction->handle($this->model_id, $data);
        $this->emit('closeModal', '#updateAdditionGroup');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.data_updated_successfully'));

        DB::commit();
    }
    public function addOption(): void
    {
        $this->options[] = ['choice_id' => null, 'price' => null, 'order' => null];
        $this->changeMinMax();
    }

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
        return view('admin::livewire.merchant.tabs.groups.modal.update');
    }
}
