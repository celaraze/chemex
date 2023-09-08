<?php

namespace Ace\Traits;

trait EloquentPlus
{
    /**
     * 提供一个MySQL支持的find_in_set()查询构建器
     * @param $column
     * @param $value
     * @return mixed
     */
    public function findInSet($column, $value)
    {
        return $this->whereRaw("FIND_IN_SET(?, $column)", $value);
    }
}