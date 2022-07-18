<?php

namespace Dcat\Admin\Grid\Column;

use Dcat\Admin\Grid\Column;

/**
 * @mixin Column
 */
class Condition
{
    /**
     * @var Column
     */
    protected $original;

    /**
     * @var Column
     */
    protected $column;

    /**
     * @var mixed
     */
    protected $condition;

    /**
     * @var bool
     */
    protected $result;

    /**
     * @var \Closure[]
     */
    protected $next = [];

    public function __construct($condition, Column $column)
    {
        $this->condition = $condition;
        $this->original = clone $column;
        $this->column = $column;
    }

    public function else(\Closure $next = null)
    {
        $self = $this;

        $condition = $this->column->if(function () use ($self) {
            return !$self->getResult();
        });

        if ($next) {
            $condition->then($next);
        }

        return $condition;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function then(\Closure $closure)
    {
        $this->next[] = $closure;

        return $this;
    }

    public function process()
    {
        if ($this->is()) {
            $this->callCallbacks($this->next);
        }
    }

    public function is()
    {
        $condition = $this->condition;

        if ($condition instanceof \Closure) {
            $condition = $this->call($condition);
        }

        return $this->result = $condition ? true : false;
    }

    protected function call(\Closure $callback, $column = null)
    {
        $column = $column ?: $this->column;

        return $callback->call($this->column->getOriginalModel(), $column);
    }

    protected function callCallbacks(array $callbacks)
    {
        if (!$callbacks) {
            return;
        }

        $column = $this->copy();

        foreach ($callbacks as $callback) {
            $this->call($callback, $column);
        }

        $this->setColumnDisplayers($column->getDisplayCallbacks());
    }

    protected function copy()
    {
        $column = clone $this->original;

        $column->setOriginalModel($this->column->getOriginalModel());
        $column->setOriginal($this->column->getOriginal());
        $column->setValue($this->column->getValue());

        return $column;
    }

    public function setColumnDisplayers(array $callbacks)
    {
        $this->column->setDisplayCallbacks($callbacks);
    }

    public function end()
    {
        return $this->if(function () {
            return true;
        });
    }

    public function reset()
    {
        $this->setColumnDisplayers($this->original->getDisplayCallbacks());
    }

    public function __call($name, $arguments)
    {
        if ($name == 'if') {
            return $this->column->if(...$arguments);
        }

        return $this->then(function ($column) use ($name, &$arguments) {
            return $column->$name(...$arguments);
        });
    }
}
