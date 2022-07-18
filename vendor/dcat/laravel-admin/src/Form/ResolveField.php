<?php

namespace Dcat\Admin\Form;

trait ResolveField
{
    protected $resolvingFieldCallbacks = [];

    /**
     * @param \Closure $callback
     * @return $this
     * @example $form->resolvingField(function ($field, $form) {
     *     ...
     * });
     *
     */
    public function resolvingField(\Closure $callback)
    {
        $this->resolvingFieldCallbacks[] = $callback;

        return $this;
    }

    public function setResolvingFieldCallbacks(array $callbacks)
    {
        $this->resolvingFieldCallbacks = $callbacks;
    }

    /**
     * @param Field $field
     * @return void
     */
    protected function callResolvingFieldCallbacks(Field $field)
    {
        foreach ($this->resolvingFieldCallbacks as $callback) {
            if ($callback($field, $this) === false) {
                break;
            }
        }
    }
}
