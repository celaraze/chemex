<?php

namespace Dcat\Admin\Grid\Column;

use Dcat\Admin\Grid\Column;
use Dcat\Admin\Grid\Events\Fetching;
use Dcat\Admin\Grid\Model;
use Dcat\Admin\Support\Helper;
use Illuminate\Contracts\Support\Renderable;

abstract class Filter implements Renderable
{
    /**
     * @var string|array
     */
    protected $class;

    /**
     * @var Column
     */
    protected $parent;

    /**
     * @var string
     */
    protected $columnName;

    /**
     * @var \Closure[]
     */
    protected $resolvings = [];

    /**
     * @var bool
     */
    protected $display = true;

    /**
     * @param array ...$params
     * @return static
     */
    public static function make(...$params)
    {
        return new static(...$params);
    }

    /**
     * @param Column $column
     */
    public function setParent(Column $column)
    {
        $this->parent = $column;

        $this->parent->grid()->listen(Fetching::class, function () {
            $this->addResetButton();

            $this->parent->grid()->model()->treeUrlWithoutQuery(
                $this->getQueryName()
            );
        });

        foreach ($this->resolvings as $closure) {
            $closure($this);
        }
    }

    /**
     * Add reset button.
     */
    protected function addResetButton()
    {
        $value = $this->value();
        if ($value === '' || $value === null) {
            return;
        }

        $style = $this->shouldDisplay() ? 'style=\'margin:3px 14px\'' : '';

        return $this->parent->addHeader(
            "&nbsp;<a class='feather icon-rotate-ccw' href='{$this->urlWithoutFilter()}' {$style}></a>"
        );
    }

    /**
     * Get filter value of this column.
     *
     * @param string $default
     * @return array|\Illuminate\Http\Request|string
     */
    public function value($default = '')
    {
        return request($this->getQueryName(), $default);
    }

    /**
     * @return string
     */
    public function getQueryName()
    {
        return $this->parent->grid()->makeName(
            'filter-' . $this->getColumnName()
        );
    }

    /**
     * Get column name.
     *
     * @return string
     */
    public function getColumnName()
    {
        return str_replace(['.', '->'], '_', $this->getOriginalColumnName());
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setColumnName(string $name)
    {
        $this->columnName = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalColumnName()
    {
        return $this->columnName ?: $this->parent->getName();
    }

    /**
     * @return bool
     */
    public function shouldDisplay()
    {
        return $this->display;
    }

    /**
     * @return string
     */
    protected function urlWithoutFilter()
    {
        return Helper::fullUrlWithoutQuery([
            $this->getQueryName(),
        ]);
    }

    /**
     * @return Column
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @param \Closure $callback
     * @return $this
     */
    public function resolving(\Closure $callback)
    {
        $this->resolvings[] = $callback;

        return $this;
    }

    /**
     * Get form action url.
     *
     * @return string
     */
    public function formAction()
    {
        return Helper::fullUrlWithoutQuery([
            $this->getQueryName(),
            $this->getColumnName(),
            $this->parent->grid()->model()->getPageName(),
            '_pjax',
        ]);
    }

    /**
     * @return $this
     */
    public function hide()
    {
        return $this->display(false);
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function display(bool $value)
    {
        $this->display = $value;

        return $this;
    }

    /**
     * Add a query binding.
     *
     * @param mixed $value
     * @param Model $model
     */
    public function addBinding($value, Model $model)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        //
    }

    /**
     * @param mixed $model
     * @param string $query
     * @param mixed array $params
     * @return void
     */
    protected function withQuery($model, string $query, array $params)
    {
        Helper::withQueryCondition($model, $this->getOriginalColumnName(), $query, $params);
    }

    /**
     * @return string
     */
    protected function renderFormButtons()
    {
        return <<<HMLT
<li class="dropdown-divider"></li>
<li>
    <button class="btn btn-sm btn-primary column-filter-submit "><i class="feather icon-search"></i></button>&nbsp;
    <a href="{$this->urlWithoutFilter()}" class="btn btn-sm btn-default"><i class="feather icon-rotate-ccw"></i></a>
</li>
HMLT;
    }

    /**
     * @param string $key
     * @return array|null|string
     */
    protected function trans($key)
    {
        return __("admin.{$key}");
    }
}
