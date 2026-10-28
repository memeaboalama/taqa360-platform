<?php

namespace Application\Admin\Livewire\Specification\Modal;

use App\Domain\Admin\Actions\Dashboard\Specification\FetchSingleSpecificationAction;
use App\Domain\Admin\Actions\Dashboard\Specification\UpdateSpecificationAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public string $name = '';
    public $description;
    public $namePre;
    public $specifications_properties = [];
    public int|null $specification_id;

    protected $listeners = ['updateSpecification', 'deletePer'];

    public function updateSpecification($id, FetchSingleSpecificationAction $fetchSingleSpecificationAction): void
    {
        $specification = $fetchSingleSpecificationAction->handle($id);
        if ($specification) {
            $this->status = $specification->status->value;
            $this->name = $specification->name;
            $this->description = $specification->description;
            $this->specification_id = $specification->id;
            $this->specifications_properties = $specification->specifications_properties()
                ->get(['name', 'status'])
                ->map(fn ($p) => ['name' => $p->name, 'status' => $p->status->value])
                ->values()
                ->toArray();
            $this->emit('openModal', '#updateSpecification');
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'required|string|in:active,inactive',
            'specifications_properties' => 'array|required',
            'specifications_properties.*.name' => 'required|string',
            'specifications_properties.*.status' => 'required|string|in:active,inactive',
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
        $this->specifications_properties[] = ['name' => $this->namePre, 'status' => "active"];
        $this->reset('namePre');
    }

    public function deletePer($index): void
    {
        unset($this->specifications_properties[$index]);
        $this->specifications_properties = array_values($this->specifications_properties);
    }

    public function store(UpdateSpecificationAction $updateSpecificationAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateSpecificationAction->handle($this->specification_id, $data);
        $this->emit('closeModal', '#updateSpecification');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::home.Specification Updated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.specification.modal.update');
    }

}
