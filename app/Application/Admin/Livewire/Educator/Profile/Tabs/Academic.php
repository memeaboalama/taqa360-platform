<?php

namespace Application\Admin\Livewire\Educator\Profile\Tabs;

use Domain\Educator\Models\Educator;
use Domain\Educator\Models\EducatorAcademicMajor;
use Domain\Educator\Models\EducatorAchievement;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\Log;

class Academic extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public $timelineItems;

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->loadTimeline();
    }

    public function loadTimeline()
    {
        $academics = $this->educator->academicMajors()->with(['approvedMajor', 'certificateFile'])->get();
        $achievements = $this->educator->achievements()->with('file')->get();

        $mappedAcademics = $academics->map(function ($item) {
            return [
                'id' => $item->id,
                'model_type' => EducatorAcademicMajor::class,
                'title' => $item->is_custom ? $item->custom_name : $item->approvedMajor?->name,
                'subtitle' => $item->graduation_institution,
                // graduation_year is a year(4) column while achievements carry a
                // string (or null), so normalise here to keep the sort stable.
                'date' => (int) $item->graduation_year,
                'description' => $this->degreeLabel($item),
                'type' => 'academic',
                'files' => $item->certificateFile ? [
                    [
                        'url' => $item->certificateFile->url,
                        'original_name' => $item->certificateFile->original_name,
                    ]
                ] : [],
                'is_approved' => $item->approval_document,
            ];
        });


        $mappedAchievements = $achievements->map(function ($item) {
            return [
                'id' => $item->id,
                'model_type' => EducatorAchievement::class,
                'title' => $item->title,
                'subtitle' => $item->organization,
                'date' => (int) ($item->start_date?->format('Y') ?: 0),
                'description' => $item->description,
                'type' => 'achievement',
                'files' => $item->file ? [
                    [
                        'url' => $item->file->url,
                        'original_name' => $item->file->original_name,
                    ]
                ] : [],
                'is_approved' => (bool) $item->approval_document,
            ];
        });


        $this->timelineItems = $mappedAcademics->merge($mappedAchievements)->sortByDesc('date');
    }

    /**
     * Read the degree without letting a stored value that is outside
     * AcademicDegreeEnum take the whole tab down: Eloquent throws a ValueError
     * from the cast, and a ValueError is an Error, not an Exception, so it used
     * to escape the catch in setApprovalStatus() and blank the page.
     */
    private function degreeLabel($item): string
    {
        try {
            return (string) ($item->degree?->trans() ?? '');
        } catch (\Throwable $e) {
            $raw = $item->getRawOriginal('degree');

            return (string) ($raw ?: __('admin::educators.no_description'));
        }
    }

    public function setApprovalStatus($type, $id, bool $status)
    {
        try {
            $modelClass = match ($type) {
                'academic', EducatorAcademicMajor::class => EducatorAcademicMajor::class,
                'achievement', EducatorAchievement::class => EducatorAchievement::class,
                default => null,
            };

            if (! $modelClass) {
                if (str_contains((string) $type, 'EducatorAcademicMajor')) {
                    $modelClass = EducatorAcademicMajor::class;
                } elseif (str_contains((string) $type, 'EducatorAchievement')) {
                    $modelClass = EducatorAchievement::class;
                }
            }

            if (! $modelClass) {
                Log::warning("Unknown model type in setApprovalStatus: {$type}");
                $msg = config('app.debug')
                    ? __('admin::educators.approval_update_failed') . " [Unknown type: {$type}]"
                    : __('admin::educators.approval_update_failed');
                $this->alert('error', $msg);
                return;
            }

            // Scope the lookup to the educator being viewed so an id from another
            // profile cannot be approved from here.
            $model = $modelClass::where('educator_id', $this->educator->id)->find($id);

            if ($model) {
                $model->update(['approval_document' => $status]);
                $this->loadTimeline();
                $this->alert('success', __('admin::educators.approval_updated'));
            } else {
                Log::warning("Model not found in setApprovalStatus: {$modelClass} ID: {$id} Educator ID: {$this->educator->id}");
                $msg = config('app.debug')
                    ? __('admin::educators.approval_update_failed') . " [Not found: {$modelClass} #{$id}]"
                    : __('admin::educators.approval_update_failed');
                $this->alert('error', $msg);
            }
        } catch (\Throwable $e) {
            Log::error("Error in setApprovalStatus: " . $e->getMessage(), [
                'exception' => $e,
                'type' => $type,
                'id' => $id,
                'status' => $status,
            ]);
            $msg = config('app.debug')
                ? __('admin::educators.approval_update_failed') . ' (' . $e->getMessage() . ')'
                : __('admin::educators.approval_update_failed');
            $this->alert('error', $msg);
        }
    }

    public function render()
    {
        return view('admin::livewire.educator.profile.tabs.academic');
    }
}
