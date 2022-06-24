<?php

namespace App\Admin\Extensions\Form;

use App\Form;
use Dcat\Admin\Form\Field\Select;
use Dcat\Admin\Support\Helper;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\View\View;

class SelectCreate extends Select
{
    protected $view = 'extensions.select_create';

    protected ?string $url = null;

    /**
     * {@inheritdoc}
     */
    public function render(): Factory|string|View
    {
        $this->addDefaultConfig([
            'allowClear' => true,
            'placeholder' => [
                'id' => '',
                'text' => $this->placeholder(),
            ],
        ]);

        $this->formatOptions();

        $this->addVariables([
            'options' => $this->options,
            'groups' => $this->groups,
            'configs' => $this->config,
            'cascadeScript' => $this->getCascadeScript(),
            'createDialog' => $this->build(),
        ]);

        $this->attribute('data-value', implode(',', Helper::array($this->value())));

        return parent::render();
    }

    protected function build(): string
    {
        Form::dialog(trans('main.select_create'))
            ->click('.create-form')
            ->url($this->url)
            ->width('1200px')
            ->height('800px');

        $text = trans('main.select_create');

        return "<span class='btn btn-primary create-form' data-url='$this->url'> $text </span>";
    }

    public function url($url): SelectCreate
    {
        $this->url = $url;

        return $this;
    }
}
