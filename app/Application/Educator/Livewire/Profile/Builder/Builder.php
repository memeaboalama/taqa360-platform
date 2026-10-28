<?php

namespace Application\Educator\Livewire\Profile\Builder;

use Livewire\Component;
use Domain\Educator\Models\Educator;
use Illuminate\Support\Facades\Auth;

class Builder extends Component
{
    public int $currentStep = 1;
    public array $completedSteps = [];
    public array $steps = [];
    public Educator $educator;

    public bool $showConfirmationModal = false;
    public string $confirmationTitle = '';
    public string $confirmationContent = '';
    public string $confirmationMethod = '';
    public $confirmationParams;

    protected $listeners = ['stepCompleted', 'goToPreviousStep', 'showDeleteConfirmation'];

    public function mount()
    {
        $this->educator = Auth::guard('educator')->user();
        $this->educator->load('profile');

        $this->steps = [
            1 => ['name' => __('educator::builder.steps.basic_info'), 'component' => 'educator::profile.builder.steps.basic'],
            2 => ['name' => __('educator::builder.steps.academic_info'), 'component' => 'educator::profile.builder.steps.academic'],
            3 => ['name' => __('educator::builder.steps.languages'), 'component' => 'educator::profile.builder.steps.languages'],
            4 => ['name' => __('educator::builder.steps.services'), 'component' => 'educator::profile.builder.steps.services'],
            5 => ['name' => __('educator::builder.steps.skills'), 'component' => 'educator::profile.builder.steps.skills'],
            6 => ['name' => __('educator::builder.steps.achievements'), 'component' => 'educator::profile.builder.steps.achievements'],
            7 => ['name' => __('educator::builder.steps.biography'), 'component' => 'educator::profile.builder.steps.biography'],
            8 => ['name' => __('educator::builder.steps.documents'), 'component' => 'educator::profile.builder.steps.documents'],
            9 => ['name' => __('educator::builder.steps.payment'), 'component' => 'educator::profile.builder.steps.payment'],
        ];

        if ($this->educator->profile?->is_complete) {
            $this->completedSteps = array_keys($this->steps);
            $this->currentStep = count($this->steps);
        } else {
            $this->currentStep = $this->educator->profile->completion_step ?? 1;

            for ($i = 1; $i < $this->currentStep; $i++) {
                $this->completedSteps[] = $i;
            }
        }
    }

    public function stepCompleted($step)
    {
        if (!in_array($step, $this->completedSteps)) {
            $this->completedSteps[] = $step;
        }

        if ($step < count($this->steps)) {
            $this->currentStep = $step + 1;
        } else {
            $this->educator->profile()->update([
                'is_complete' => true,
                'completion_step' => count($this->steps),
            ]);
            session()->flash('status', 'تم إكمال ملفك الشخصي بنجاح!');
            return redirect()->route('educator:home');
        }

        $this->educator->profile()->update(['completion_step' => $this->currentStep]);
    }

    public function goToPreviousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function changeStep($stepId)
    {
        $isAccessible = $stepId <= $this->currentStep || in_array($stepId, $this->completedSteps);
        if ($isAccessible && $stepId <= count($this->steps)) {
            $this->currentStep = $stepId;
        }
    }

    public function showDeleteConfirmation(string $title, string $content, string $method, $params)
    {
        $this->confirmationTitle = $title;
        $this->confirmationContent = $content;
        $this->confirmationMethod = $method;
        $this->confirmationParams = $params;
        $this->showConfirmationModal = true;
    }

    public function executeConfirmedDelete()
    {
        $this->emitTo(
            $this->steps[$this->currentStep]['component'],
            'executeDelete',
            $this->confirmationMethod,
            $this->confirmationParams
        );

        $this->showConfirmationModal = false;
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.builder');
    }
}
