<?php

namespace Dcat\Admin\Grid\Displayers;

abstract class Editable extends AbstractDisplayer
{
    protected $type;

    protected $view;

    protected $options = [
        // 是否刷新页面
        'refresh' => false,
    ];

    public function display($options = [])
    {
        if (is_bool($options)) {
            $options = ['refresh' => $options];
        }

        $this->options = array_merge($this->options, $options);

        return admin_view($this->view, array_merge($this->variables(), $this->defaultOptions() + $this->options));
    }

    public function variables()
    {
        return [
            'key' => $this->getKey(),
            'class' => $this->getSelector(),
            'name' => $this->getName(),
            'type' => $this->type,
            'display' => $this->getValue(),
            'value' => $this->getOriginal(),
            'url' => $this->getUrl(),
        ];
    }

    protected function getSelector()
    {
        return 'grid-editable-' . $this->type;
    }

    protected function getName()
    {
        return $this->column->getName();
    }

    protected function getValue()
    {
        return $this->value;
    }

    protected function getOriginal()
    {
        return $this->column->getOriginal();
    }

    protected function getUrl()
    {
        return $this->resource() . '/' . $this->getKey();
    }

    protected function defaultOptions()
    {
        return [];
    }
}
