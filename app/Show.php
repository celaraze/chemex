<?php

namespace App;

use Dcat\Admin\Show\Field;

class Show extends \Dcat\Admin\Show
{
    /**
     * Add a model field to show.
     *
     * @param $name
     * @param string $label
     * @param array $sorts
     * @return Field
     */
    public function field($name, $label = '', $sorts = []): Field
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

        return $this->addField($name, $label, (int)$order);
    }

    /**
     * Add a model field to show.
     *
     * @param $name
     * @param string $label
     * @param int $order
     * @return Field
     */
    protected function addField($name, $label = '', $order = 99): Field
    {
        $field = new Field($name, $label);

        $field->__order__ = $order;

        $field->setParent($this);

        $this->overwriteExistingField($name);

        $this->fields->push($field);

        return $field;
    }

    /**
     * Render the show panels.
     *
     * @return string
     */
    public function render(): string
    {
        $model = $this->model();

        if (is_callable($this->builder)) {
            call_user_func($this->builder, $this);
        }

        if ($this->fields->isEmpty()) {
            $this->all();
        }

        if (is_array($this->builder)) {
            $this->fields($this->builder);
        }

        $this->fields->each->fill($model);
        $this->relations->each->model($model);

        $this->callComposing();

        $array = [];
        $fields = $this->fields->toArray();
        $keys = array_column($this->fields->toArray(), '__order__');
        array_multisort($keys, SORT_ASC, $fields);

        $array = array_merge($array, $fields);

        $data = [
            'panel' => $this->panel->fill($array),
            'relations' => $this->relations,
        ];

        return view($this->view, $data)->render();
    }
}
