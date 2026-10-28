<?php

namespace Application\Admin\Livewire\Specification\Modal;

use App\Domain\Admin\Actions\Dashboard\Specification\StoreSpecificationAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Domain\AppBase\Models\SpecificationProperties;
use Illuminate\Http\Request;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public  $specifications_properties=[] ;
    public $name;
    public $namePre;
    public $description;
    protected $listeners = ['deletePer'];
    protected function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'required|string|in:active,inactive',
            'specifications_properties' => 'array|required',
            'specifications_properties.*.name' => 'required|string',
            'specifications_properties.*.status' => 'required|string|in:active,inactive'
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }
    public function updateStatusRow($indx): void
    {
        if ($this->specifications_properties[$indx]['status'] == "active") {
            $this->specifications_properties[$indx]['status'] = "inactive";
        } else {
            $this->specifications_properties[$indx]['status'] = "active";
        }
    }

    public function createPer(): void
{
    $this->validate([
        'namePre' => 'required|string',
    ]);
    $this->specifications_properties []=['name'=>$this->namePre,'status'=>"active"];
    $this->reset('namePre');
}
public function deletePer($index): void
    {
        unset($this->specifications_properties[$index]);
        $this->specifications_properties = array_values($this->specifications_properties);
    }
    public function store(StoreSpecificationAction $storeSpecificationAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeSpecificationAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createSpecification');
        $this->alert('success', __('admin::home.Specification Created Successfully'));
        $this->reset('status', 'name','description','specifications_properties');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.specification.modal.create');
    }
}
