<?php

namespace Application\Admin\Livewire\Specification\Table;

use App\Domain\Catalog\Models\Specification;
use Domain\MarketPlace\Models\Catalog\ChildCategory;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Catalog\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class All extends Component
{
    use WithPagination;

    public string $search = '';
    public string $categoryId = '';
    public string $status = '';
    public string $perPage = '10';

    protected $listeners = ['refreshDatatable' => '$refresh'];
    protected $paginationTheme = 'bootstrap';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'categoryId', 'status', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function export()
    {
        $this->validateFilters();

        $specifications = $this->filteredSpecifications()
            ->with(['categories:id,title', 'categoryLinks'])
            ->withCount('properties')
            ->orderByDesc('id')
            ->get();
        $categoryLinks = $specifications->flatMap(fn ($specification) => $specification->categoryLinks);
        $linkedCategoryNames = [
            'subcategory' => Subcategory::query()
                ->whereIn('id', $categoryLinks->where('category_type', 'subcategory')->pluck('category_id'))
                ->get(['id', 'title'])
                ->mapWithKeys(fn ($category) => [$category->id => $category->title]),
            'child_category' => ChildCategory::query()
                ->whereIn('id', $categoryLinks->where('category_type', 'child_category')->pluck('category_id'))
                ->get(['id', 'title'])
                ->mapWithKeys(fn ($category) => [$category->id => $category->title]),
        ];

        return response()->streamDownload(function () use ($specifications, $linkedCategoryNames): void {
            $output = fopen('php://output', 'w');
            if ($output === false) {
                throw new \RuntimeException('Unable to open the export stream.');
            }

            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                __('admin::home.Name'),
                __('admin::home.Description'),
                __('admin::home.specification_categories'),
                __('admin::home.specification_properties'),
                __('admin::home.status'),
            ]);

            foreach ($specifications as $specification) {
                fputcsv($output, [
                    $specification->name,
                    $specification->description,
                    $specification->categories->pluck('title')
                        ->merge($specification->categoryLinks->map(fn ($link) => $linkedCategoryNames[$link->category_type][$link->category_id] ?? null)->filter())
                        ->implode(', '),
                    $specification->properties_count,
                    $this->statusLabel($specification),
                ]);
            }

            fclose($output);
        }, 'specification-templates-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $this->validateFilters();

        $baseQuery = Specification::query()->isMaster();
        $templates = $this->filteredSpecifications()
            ->with(['categories:id,title'])
            ->withCount(['properties', 'categories', 'categoryLinks'])
            ->orderByDesc('id')
            ->paginate((int) $this->perPage);

        return view('admin::livewire.specification.table.all', [
            'templates' => $templates,
            'categories' => Category::query()->orderBy('title')->get(['id', 'title']),
            'stats' => [
                'total' => (clone $baseQuery)->count(),
                'published' => (clone $baseQuery)->where('status', 'active')->count(),
                'subcategories' => ChildCategory::query()->count(),
            ],
        ]);
    }

    private function filteredSpecifications(): Builder
    {
        return Specification::query()
            ->isMaster()
            ->when(trim($this->search) !== '', function (Builder $query): void {
                $search = trim($this->search);
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%');

                    if (ctype_digit($search)) {
                        $query->orWhere('id', (int) $search);
                    }
                });
            })
            ->when($this->categoryId !== '', function (Builder $query): void {
                $categoryId = (int) $this->categoryId;
                $query->where(function (Builder $query) use ($categoryId): void {
                    $query->whereHas('categories', fn (Builder $categories) => $categories->whereKey($categoryId))
                        ->orWhereHas('categoryLinks', function (Builder $links) use ($categoryId): void {
                            $links->where(function (Builder $query) use ($categoryId): void {
                                $query->where(fn (Builder $query) => $query
                                    ->where('category_type', 'subcategory')
                                    ->where('is_excluded', false)
                                    ->whereIn('category_id', Subcategory::query()->select('id')->where('category_id', $categoryId)))
                                    ->orWhere(fn (Builder $query) => $query
                                        ->where('category_type', 'child_category')
                                        ->where('is_excluded', false)
                                        ->whereIn('category_id', ChildCategory::query()->select('child_categories.id')
                                            ->join('sub_categories', 'sub_categories.id', '=', 'child_categories.subcategory_id')
                                            ->where('sub_categories.category_id', $categoryId)));
                            });
                        });
                });
            })
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status));
    }

    private function validateFilters(): void
    {
        $this->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'categoryId' => ['nullable', 'integer', 'exists:categories,id'],
            'status' => ['nullable', 'in:active,inactive,draft,rejected'],
            'perPage' => ['required', 'in:10,20,50'],
        ]);
    }

    private function statusLabel(Specification $specification): string
    {
        $status = (string) $specification->getRawOriginal('status');
        if (!in_array($status, ['active', 'inactive', 'draft', 'rejected'], true)) {
            return __('admin::home.specification_status_unknown');
        }

        return __('admin::home.specification_status_' . $status);
    }
}
