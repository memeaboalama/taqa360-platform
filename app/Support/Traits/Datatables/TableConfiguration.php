<?php

namespace Support\Traits\Datatables;

trait TableConfiguration
{
    public int $index = 0;

    public function bootWithSorting(): void
    {
        $this->defaultSortColumn = 'id';
        $this->defaultSortDirection = 'desc';
    }

    public function initializeTableConfiguration(): void
    {
        $this->setDefaultSort('id', 'desc');
        $this->listeners = array_merge($this->listeners, [
           'refreshDatatable'  =>  'resetIndex'
        ]);
    }

    public function configure() : void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
        $this->setBulkActionsEnabled();

        $this->setHideBulkActionsWhenEmptyEnabled();

        $this->setBulkActions([
            'deleteSelected' => 'Delete Selected',
        ]);

        $this->setTableWrapperAttributes([
            'class' => 'table text-nowrap',
        ]);

        $this->setTrAttributes(function($row) {
                return [
                'class' => '',
                ];
        });

        $this->setThAttributes(function($row) {
                return [
                'class' => '',
                ];
        });

        $this->setTbodyAttributes([
                'class' => '',
        ]);
    }

    public function deleteSelected()
    {
        $this->emit('deleteAll',$this->getSelected());
    }

    public function resetIndex()
    {
        $this->index = 0;
        // NOTE: initializeTableConfiguration() maps the `refreshDatatable` event to this
        // method (overwriting any `$refresh` mapping on the component), so trigger a
        // re-render here as well. Otherwise tables (e.g. pending/approved drivers)
        // keep showing stale rows after approve/delete actions.
        $this->emitSelf('$refresh');
    }

}
