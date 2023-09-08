<?php

namespace Ace;

class Arr
{
    /**
     * 将数组分页处理，并返回一个多维数组.
     *
     * @param $array
     * @param $count
     * @return array
     */
    public static function paginate($array, $count)
    {
        $return = [];
        for ($i = 0; $i < count($array); $i += $count) {
            $chuck = array_slice($array, $i, $count);
            array_push($return, $chuck);
        }
        return $return;
    }
}