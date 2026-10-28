<?php

namespace Application\Admin\Livewire\Package\Modal;

use Application\Admin\Livewire\Package\Modal\Create\Ar\Quill as QuillAr;
use Application\Admin\Livewire\Package\Modal\Create\En\Quill as QuillEn;
use App\Domain\Admin\Actions\Dashboard\Package\StorePackageAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Create extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $terms_en, $terms_ar, $commission;
    public  $type_services;
    public  $package_type_services = [];
    public $listeners = [
        QuillEn::EVENT_CREATE_VALUE_UPDATED_EN,
        QuillAr::EVENT_CREATE_VALUE_UPDATED_AR,
    ];
    public function mount(): void
    {
        $this->type_services = \App\Domain\AppBase\Models\TypeService::where(['status' =>  'active'])->get();
        $this->commission = 0;
        $this->fullPackageTypeService();
    }
    public function fullPackageTypeService(): void
    {
        foreach ($this->type_services as $type_services) {
            $this->package_type_services[] = [
                "type_service_id" => $type_services->id,
                "type_service_name" => $type_services->name,
                "commission" => 0,
                "discount_rate" => 0,
                "desc" => '',
                "status" => 'inactive'
            ];
        }
    }
    public function updateStatusRow($indx): void
    {
        // Log::debug($indx);
        // Log::debug(json_encode( $this->package_type_services));
        if ($this->package_type_services[$indx]['status'] == "active") {
            $this->package_type_services[$indx]['status'] = "inactive";
        } else {
            $this->package_type_services[$indx]['status'] = "active";
        }
        //    Log::debug(json_encode( $this->package_type_services));
    }
    public function quill_create_value_updated_en($value)
    {
        $this->terms_en = $value;
    }

    public function quill_create_value_updated_ar($value)
    {
        $this->terms_ar = $value;
    }
    public function nickNames()
    {
        return [
            'title.ar' => trans('admin::home.Title'),
            'title.en' => trans('admin::home.Title'),
            'description.en' => trans('admin::home.Description'),
            'description.ar' => trans('admin::home.Description'),
            'terms_en' => trans('admin::home.Terms & Conditions in English'),
            'terms_ar' => trans('admin::home.Terms & Conditions in Arabic'),
            'commission' => trans('admin::home.commission'),
        ];
    }

    protected function rules(): array
    {
        return [
            'title.en' => 'required|string|min:2|max:120',
            'title.ar' => 'required|string|min:2|max:120',
            'description.ar' => 'required|string|min:2',
            'description.en' => 'required|string|min:2',
            'terms_en' => 'required',
            'terms_ar' => 'required',
            'commission' => 'required',
            'status' => 'required|string|in:active,inactive',
            'package_type_services' => 'array|required',
            'package_type_services.*.type_service_id' => 'required|numeric|distinct',
            'package_type_services.*.commission' => 'required|numeric',
            'package_type_services.*.discount_rate' => 'required|numeric',
            'package_type_services.*.desc' => '',
            'package_type_services.*.status' => 'required',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StorePackageAction $storePackageAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        try {
            DB::beginTransaction();
            $storePackageAction->handle($data);
            $this->alert('success', __('admin::lang.Added Successfully'));
            $this->emit('closeModal', '#createPackage');
            $this->emit('refreshDatatable');
            $this->reset('title', 'description', 'terms_en', 'terms_ar', 'status');
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Package Create Error: ' . $e->getMessage());
            $this->alert('error', __('admin::lang.Something went wrong'));
        }
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.package.modal.create');
    }
}
