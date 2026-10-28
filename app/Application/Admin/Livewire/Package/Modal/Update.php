<?php

namespace Application\Admin\Livewire\Package\Modal;

use Application\Admin\Livewire\Package\Modal\Update\Ar\Quill as QuillAr;
use Application\Admin\Livewire\Package\Modal\Update\En\Quill as QuillEn;
use App\Domain\Admin\Actions\Dashboard\Package\FetchSinglePackageAction;
use App\Domain\Admin\Actions\Dashboard\Package\UpdatePackageAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Update extends Component
{
    use LivewireAlert;

    public string $status = 'active';
    public int|null $package_id = null;
    public $package;
    public array $title = ['en' => null];
    public array $description = ['en' => null];
    public $terms_en, $terms_ar, $commission;
    public  $type_services,$type_services_Package;
    public  $package_type_services=[];
    protected $listeners = [
        QuillEn::EVENT_UPDATE_VALUE_UPDATED_EN,
        QuillAr::EVENT_UPDATE_VALUE_UPDATED_AR,
        'editPackage',
    ];

    public function editPackage($id, FetchSinglePackageAction $fetchSinglePackageAction): void
    {
        $this->package = $fetchSinglePackageAction->handle($id);
        if ($this->package) {
            $this->title = $this->package->getTranslations('title');
            $this->description = $this->package->getTranslations('description');
            $this->status = $this->package->status->value;
            $this->package_id = $this->package->id;
            $this->terms_en = $this->package->terms_en;
            $this->terms_ar = $this->package->terms_ar;
            $this->commission = $this->package->commission;
            $this->type_services_Package = \App\Domain\AppBase\Models\PackageTypeServices::with('type_service')->where(['package_id' => $this->package->id])->get();
            $this->fullPackageTypeService();

            $this->dispatchBrowserEvent('populate-package-editors', [
                'en' => $this->terms_en,
                'ar' => $this->terms_ar,
            ]);
        }
        $this->emit('openModal', '#updatePackage');
    }
    public function fullPackageTypeService(): void
    {
        $this->type_services = \App\Domain\AppBase\Models\TypeService::where(['status' =>  'active'])->get();
        $type_servicesNew=[];
        $this->package_type_services=[];
        foreach($this->type_services as $type_services)
        {
            $type_servicesNew[] =["type_service_id"=>$type_services->id,"type_service_name"=>$type_services->name,
            "commission"=>0,"discount_rate"=>0,"desc"=>'',"status"=>'inactive'];
        }
        $this->package_type_services=$this->type_services_Package->union($type_servicesNew)->toArray();
    }
    public function updateStatusRow($val,$indx): void
    {
        $this->package_type_services[$indx]['status'] = $val;
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
    public function quill_update_value_updated_en($value){
        $this->terms_en = $value;
    }

    public function quill_update_value_updated_ar($value){
        $this->terms_ar = $value;
    }

    public function store(UpdatePackageAction $updatePackageAction): void
    {
        $data = $this->validate(attributes: $this->nickNames());
        try {
            DB::beginTransaction();
            DB::table('package_type_services')->where(['package_id' =>  $this->package_id])->delete();
            $updatePackageAction->handle($this->package_id, $data);
            $this->alert('success', __('admin::lang.Updated Successfully'));
            $this->emit('closeModal', '#updatePackage');
            $this->emit('refreshDatatable');
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Package Update Error: ' . $e->getMessage());
            $this->alert('error', __('admin::lang.Something went wrong'));
        }
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.package.modal.update');
    }
}
