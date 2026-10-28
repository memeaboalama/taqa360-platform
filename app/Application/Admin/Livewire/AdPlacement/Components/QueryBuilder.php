<?php

namespace Application\Admin\Livewire\AdPlacement\Components;

use App\Domain\AppBase\Actions\Product\ExecuteProductQueryAction;
use App\Support\Enum\FilterConditionEnum;
use App\Support\Enum\ProductFilterFieldEnum;
use App\Support\Enum\ProductQueryPresetEnum;
use App\Support\Enum\ProductQueryTypeEnum;
use App\Support\Enum\ProductSortFieldEnum;
use Livewire\Component;

class QueryBuilder extends Component
{
    public string $tabKey = '';
    public array $query = [];
    public string $mode = 'preset'; // preset, custom, expert
    public int $previewLimit = 5;
    public $previewProducts = [];
    public bool $showPreview = false;

    protected $rules = [
        'query.type' => 'required|string',
        'query.preset' => 'nullable|string',
        'query.filters' => 'nullable|array',
        'query.sort' => 'nullable|array',
        'query.groups' => 'nullable|array',
    ];

    public function mount($tabKey = '', $existingQuery = [])
    {
        $this->tabKey = $tabKey;
        $this->query = $existingQuery ?: $this->getDefaultQuery();
        $this->mode = $this->query['type'] ?? 'preset';
    }

    public function getDefaultQuery(): array
    {
        return [
            'type' => ProductQueryTypeEnum::PRESET->value,
            'preset' => ProductQueryPresetEnum::LATEST->value,
            'config' => ['days' => 30],
            'filters' => [],
            'sort' => [],
            'groups' => [],
        ];
    }

    public function updatedMode($value)
    {
        $this->query['type'] = $value;
        
        // Initialize based on mode
        if ($value === 'preset' && empty($this->query['preset'])) {
            $this->query['preset'] = ProductQueryPresetEnum::LATEST->value;
            $this->query['config'] = ['days' => 30];
        } elseif ($value === 'custom' && empty($this->query['filters'])) {
            $this->query['filters'] = [];
            $this->query['sort'] = [['field' => 'created_at', 'order' => 'desc']];
        } elseif ($value === 'expert' && empty($this->query['groups'])) {
            $this->query['groups'] = [
                [
                    'logic' => 'AND',
                    'filters' => []
                ]
            ];
        }
    }

    public function addFilter()
    {
        if ($this->mode === 'custom') {
            $this->query['filters'][] = [
                'field' => ProductFilterFieldEnum::CREATED_AT->value,
                'condition' => FilterConditionEnum::LAST_N_DAYS->value,
                'value' => 30
            ];
        }
    }

    public function removeFilter($index)
    {
        if ($this->mode === 'custom') {
            unset($this->query['filters'][$index]);
            $this->query['filters'] = array_values($this->query['filters']);
        }
    }

    public function addSort()
    {
        if ($this->mode === 'custom') {
            $this->query['sort'][] = [
                'field' => ProductSortFieldEnum::CREATED_AT->value,
                'order' => 'desc'
            ];
        }
    }

    public function removeSort($index)
    {
        if ($this->mode === 'custom') {
            unset($this->query['sort'][$index]);
            $this->query['sort'] = array_values($this->query['sort']);
        }
    }

    public function addGroup()
    {
        if ($this->mode === 'expert') {
            $this->query['groups'][] = [
                'logic' => 'AND',
                'filters' => []
            ];
        }
    }

    public function removeGroup($index)
    {
        if ($this->mode === 'expert') {
            unset($this->query['groups'][$index]);
            $this->query['groups'] = array_values($this->query['groups']);
        }
    }

    public function addGroupFilter($groupIndex)
    {
        if ($this->mode === 'expert') {
            $this->query['groups'][$groupIndex]['filters'][] = [
                'field' => ProductFilterFieldEnum::CREATED_AT->value,
                'condition' => FilterConditionEnum::EQUALS->value,
                'value' => ''
            ];
        }
    }

    public function removeGroupFilter($groupIndex, $filterIndex)
    {
        if ($this->mode === 'expert') {
            unset($this->query['groups'][$groupIndex]['filters'][$filterIndex]);
            $this->query['groups'][$groupIndex]['filters'] = array_values($this->query['groups'][$groupIndex]['filters']);
        }
    }

    public function previewQuery()
    {
        try {
            $action = new ExecuteProductQueryAction();
            $this->previewProducts = $action->handle($this->query, $this->previewLimit);
            $this->showPreview = true;
            
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => __('admin::lang.Preview loaded') . ': ' . $this->previewProducts->count() . ' ' . __('admin::lang.products')
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => __('admin::lang.Error loading preview') . ': ' . $e->getMessage()
            ]);
        }
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function render()
    {
        return view('admin::livewire.ad-placement.components.query-builder', [
            'presetOptions' => ProductQueryPresetEnum::cases(),
            'filterFields' => ProductFilterFieldEnum::cases(),
            'sortFields' => ProductSortFieldEnum::cases(),
            'conditions' => FilterConditionEnum::cases(),
        ]);
    }
}
