<?php

namespace App;

use Dcat\Admin\Grid\Column;

class Grid extends \Dcat\Admin\Grid
{
    /**
     * Add column to Grid.
     *
     * @param string $name
     * @param string $label
     * @param array $sorts
     *
     * @return Column
     */
    public function column($name, $label = '', array $sorts = []): Column
    {
        $order = 99;
        if (!empty($sorts)) {
            $field = array_column($sorts, 'name');
            $key = array_search($name, $field);
            if ($key !== false) {
                if (isset($sorts[$key])) {
                    $order = $sorts[$key]['order'];
                }
            }
        }

        return $this->addColumn($name, $label, (int)$order);
    }

    /**
     * Add column to grid.
     *
     * @param int $order
     * @param string $field
     * @param string $label
     *
     * @return Column
     */
    protected function addColumn($field = '', $label = '', int $order = 99): Column
    {
        $column = $this->newColumn($field, $label, $order);

        $this->columns->put($field, $column);
        $this->allColumns->put($field, $column);

        return $column;
    }

    /**
     * @param int $order
     * @param string $field
     * @param string $label
     *
     * @return Column
     */
    public function newColumn($field = '', $label = '', int $order = 99): Column
    {
        $column = new Column($field, $label);
        $column->setGrid($this);
        if ((substr($field, 0, 2) != '__')) {
            $column->__order__ = $order;
        }

        return $column;
    }

    /**
     * Build the grid.
     *
     * @return void
     */
    public function build()
    {
        if ($this->built) {
            return;
        }

        $collection = clone $this->processFilter();

        $this->prependRowSelectorColumn();
        $this->appendActionsColumn();

        Column::setOriginalGridModels($collection);

        $columns = $this->columns->toArray();
        $array = [];
        if (isset($columns['__row_selector__'])) {
            $__row_selector__ = $columns['__row_selector__'];
            unset($columns['__row_selector__']);
            $array = array_merge($array, ['__row_selector__' => $__row_selector__]);
        }
        $__actions__ = $columns['__actions__'];
        unset($columns['__actions__']);
        $keys = array_column($columns, '__order__');
        array_multisort($keys, SORT_ASC, $columns);
        $array = array_merge($array, $columns);
        $array = array_merge($array, ['__actions__' => $__actions__]);
        $this->columns = collect($array);

        $this->columns->map(function (Column $column) use (&$collection) {
            $column->fill($collection);

            $this->columnNames[] = $column->getName();
        });

        $this->buildRows($collection);

        $this->sortHeaders();
    }
}
