<?php

namespace Dcat\Admin\Grid\Filter\Presenter;

use Dcat\Admin\Admin;

class Text extends Presenter
{
    /**
     * @var string
     */
    protected $placeholder = '';

    /**
     * @var string
     */
    protected $icon = 'pencil';

    /**
     * @var string
     */
    protected $type = 'text';

    /**
     * Text constructor.
     *
     * @param string $placeholder
     */
    public function __construct($placeholder = '')
    {
        $this->placeholder($placeholder);
    }

    /**
     * Set input placeholder.
     *
     * @param string $placeholder
     * @return $this
     */
    public function placeholder($placeholder = '')
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get variables for field template.
     *
     * @return array
     */
    public function defaultVariables(): array
    {
        return [
            'placeholder' => $this->placeholder,
            'icon' => $this->icon,
            'type' => $this->type,
            'group' => $this->filter->group,
        ];
    }

    /**
     * @return Text
     */
    public function url()
    {
        return $this->inputmask(['alias' => 'url'], 'internet-explorer');
    }

    /**
     * @param array $options
     * @param string $icon
     * @return $this
     */
    public function inputmask($options = [], $icon = 'pencil')
    {
        Admin::js('@jquery.inputmask');

        $options['rightAlign'] = false;

        $options = json_encode($options);

        Admin::script("$('#{$this->filter->parent()->filterID()} input.{$this->filter->getId()}').inputmask($options);");

        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Text
     */
    public function email()
    {
        return $this->inputmask(['alias' => 'email'], 'envelope');
    }

    /**
     * @return Text
     */
    public function integer()
    {
        return $this->inputmask(['alias' => 'integer']);
    }

    /**
     * @param array $options
     *
     * @return Text
     * @see https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
     *
     */
    public function decimal($options = [])
    {
        return $this->inputmask(array_merge($options, ['alias' => 'decimal']));
    }

    /**
     * @param array $options
     *
     * @return Text
     * @see https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
     *
     */
    public function currency($options = [])
    {
        return $this->inputmask(array_merge($options, [
            'alias' => 'currency',
            'prefix' => '',
            'removeMaskOnSubmit' => true,
        ]));
    }

    /**
     * @param array $options
     *
     * @return Text
     * @see https://github.com/RobinHerbots/Inputmask/blob/4.x/README_numeric.md
     *
     */
    public function percentage($options = [])
    {
        $options = array_merge(['alias' => 'percentage'], $options);

        return $this->inputmask($options);
    }

    /**
     * @return Text
     */
    public function ip()
    {
        return $this->inputmask(['alias' => 'ip'], 'laptop');
    }

    /**
     * @return Text
     */
    public function mac()
    {
        return $this->inputmask(['alias' => 'mac'], 'laptop');
    }

    /**
     * @param string $mask
     * @return Text
     */
    public function mobile($mask = '19999999999')
    {
        return $this->inputmask(compact('mask'), 'phone');
    }
}
