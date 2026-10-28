<?php

namespace Application\Marketplace\Livewire\Branch\Tabs;

use App\Domain\AppBase\Actions\Days\FetchDays;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\WorkingDays\DeleteWorkingDayHourAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\WorkingDays\DeleteWorkingDaysAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\WorkingDays\FetchWorkingDaysAction;
use App\Domain\MarketPlace\Actions\Dashboard\Branch\WorkingDays\UpdateWorkingDaysAction;
use App\Support\Rules\WorkingHoursRule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class Working extends Component
{
    use LivewireAlert;

    public Collection $days;
    public int $branch_id;
    public $items;
    public \Illuminate\Database\Eloquent\Collection $branchWorkingDays;

    protected $listeners = ['refreshHours' => '$refresh', 'refresh_hours' => 'refresh_hours'];


    public function mount($branch_id, FetchDays $fetchDays, FetchWorkingDaysAction $fetchWorkingDaysAction): void
    {
        $this->branch_id = $branch_id;
        $this->days = $fetchDays->handle();
        $this->branchWorkingDays = $fetchWorkingDaysAction->handle($this->branch_id);
        $this->populateDays();
    }

    protected function rules(): array
    {
        return [
            'items.*.status' => 'sometimes',
            'items.*.id' => 'required',
            'items.*.hours.*.from' => ['required',new WorkingHoursRule($this->branch_id, $this->all())],
            'items.*.hours.*.to' => 'required',
            'items.*.hours.*.status' => 'required',
        ];
    }

    protected function validationAttributes(): array
    {
        $attributes = [];
        foreach ($this->days as $day) {
            if (!isset($this->items[$day->name]['hours'])) {
                continue;
            }
            foreach ($this->items[$day->name]['hours'] as $key => $hour) {
                $prefix = 'items.' . $day->name . '.hours.' . $key . '.';
                $attributes[$prefix . 'from'] = __('marketplace::home.From');
                $attributes[$prefix . 'to'] = __('marketplace::home.To');
                $attributes[$prefix . 'status'] = __('marketplace::home.status');
            }
        }
        return $attributes;
    }

    public function store(UpdateWorkingDaysAction $updateWorkingDaysAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateWorkingDaysAction->handle($this->branch_id, $data);
        DB::commit();
        $this->emit( 'refresh_hours', $this->branch_id);
        $this->alert('success', __('marketplace::home.data_updated_successfully'));

    }

    protected function populateDays()
    {
        foreach ($this->days as $day)
        {
            $this->items[$day->name]['id'] = $day->id;
            foreach ($this->branchWorkingDays as $key => $workDay)
            {
                if ($workDay)
                {
                    if ($workDay->day_id == $day->id)
                    {
                        $this->items[$day->name]['hours'] = $workDay
                            ->workHours()
                            ->select(['id', 'from', 'to', 'status'])
                            ->get()
                            ->toArray();
                        $this->items[$day->name]['status'] = $workDay->status;
                    }
                }
            }
        }
        if($this->items == null)
            $this->items = [];

    }

    public function refresh_hours($branch_id, FetchDays $fetchDays, FetchWorkingDaysAction $fetchWorkingDaysAction)
    {
        $this->branch_id = $branch_id;
        $this->days = $fetchDays->handle();
        $this->branchWorkingDays = $fetchWorkingDaysAction->handle($this->branch_id);
        $this->populateDays();
    }

    public function addItem($day)
    {
        $data = [
            'id' => 0,
            'from' => '',
            'to' => '',
            'status' => 'active',
        ];
        if (!isset($this->items[$day]['hours']))
        {
            $this->items[$day]['hours'] = [];
        }
        array_push($this->items[$day]['hours'], $data);
    }

    public function removeItem($day , $day_id, $hour_key, $hour_id = null, DeleteWorkingDayHourAction $deleteWorkingDayHourAction)
    {
        if ($hour_id != null)
        {
            DB::beginTransaction();
            $deleteWorkingDayHourAction->handle($this->branch_id, $day_id, $hour_id);
            DB::commit();
            $this->emit( 'refresh_hours', $this->branch_id);
            $this->alert('success', __('marketplace::home.data_updated_successfully'));
        }
        unset($this->items[$day]['hours'][$hour_key]);
    }

    public $deleteDay, $deleteDayId, $deleteHourKey, $deleteHourId, $deleting = false;

    public function confirmDelete($day, $day_id, $hour_key, $hour_id = null): void
    {
        $this->deleteDay = $day;
        $this->deleteDayId = $day_id;
        $this->deleteHourKey = $hour_key;
        $this->deleteHourId = $hour_id;
        $this->emit('openModal', '#deleteHourModal');
    }

    public function confirmDeleteHour(DeleteWorkingDayHourAction $deleteWorkingDayHourAction): void
    {
        $this->deleting = true;
        try {
            DB::beginTransaction();
            if ($this->deleteHourId != null) {
                $deleteWorkingDayHourAction->handle($this->branch_id, $this->deleteDayId, $this->deleteHourId);
            }
            unset($this->items[$this->deleteDay]['hours'][$this->deleteHourKey]);
            $this->items[$this->deleteDay]['hours'] = array_values($this->items[$this->deleteDay]['hours'] ?? []);
            DB::commit();
            $this->emit( 'refresh_hours', $this->branch_id);
            $this->alert('success', __('marketplace::home.data_updated_successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->alert('error', __('marketplace::home.please_fix_the_errors'));
        } finally {
            $this->deleting = false;
            $this->emit('closeModal', '#deleteHourModal');
        }
    }

    public function updateDayStatus($day, $val): void
    {
        $this->items[$day]['status'] = $val;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.branch.tabs.working', [
            'items' =>  $this->items
        ]);
    }
}
