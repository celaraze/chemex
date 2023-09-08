<?php

namespace Ace;

use Exception;

class Uni
{
    /**
     * 提取字符串中的数字.
     *
     * @param string $string 需要处理的字符串
     * @return string 处理结果
     */
    static function getNumFromString(string $string = '')
    {
        $string = trim($string);
        if (empty($string)) {
            return '';
        }
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            if (is_numeric($string[$i])) {
                $result .= $string[$i];
            }
        }
        return $result;
    }

    /**
     * 截取指定字符中间的内容.
     *
     * @param string $begin 开始字符串
     * @param string $end 结束字符串
     * @param string $string 需要处理的字符串
     * @return string
     */
    static function cutString(string $begin, string $end, string $string)
    {
        $b = mb_strpos($string, $begin) + mb_strlen($begin);
        $e = mb_strpos($string, $end) - $b;

        return mb_substr($string, $b, $e);
    }

    /**
     * 给json object string加双引号.
     *
     * @param string $string 需要处理的json字符串
     * @return string|array|null 处理结果
     */
    static function jsonStringify(string $string)
    {
        if (preg_match('/\w:/', $string)) {
            $string = preg_replace('/(\w+):/is', '"$1":', $string);
        }
        return $string;
    }

    /**
     * 去除字符串中的各种换行符和空格.
     *
     * @param string $string 需要处理的字符串
     * @return string 处理结果
     */
    static function trim(string $string)
    {
        $string = preg_replace("/\n/", '', $string);
        $string = preg_replace("/\r/", '', $string);
        $string = preg_replace("/\t/", '', $string);
        $string = preg_replace("/ /", '', $string);
        return trim($string);
    }

    /**
     * 返回字符串中的中文.
     *
     * @param string $string 需要处理的字符串
     * @return string 处理结果
     */
    static function getChineseFromString(string $string)
    {
        preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $string, $chinese);
        return implode('', $chinese[0]);
    }

    /**
     * 比较两个数组中的差异值，返回$arrayA比$arrayB中多的值
     * @param array $arrayA 前者数组
     * @param array $arrayB 后者数组
     * @return array 处理得到前者比后者多的值
     */
    static function diffTwoArray(array $arrayA, array $arrayB)
    {
        $result = array();
        foreach ($arrayA as $a) {
            if (!in_array($a, $arrayB)) {
                array_push($result, $a);
            }
        }
        return array_unique($result);
    }

    /**
     * 生成随机RGB颜色.
     *
     * @param int $min 颜色的最小值
     * @param int $max 颜色的最大值
     * @param int $stringify 是否以r,g,b格式输出，默认否
     * @return string|array 处理结果
     */
    static function randomRGB(int $min, int $max, int $stringify = 0)
    {
        $r = rand($min, $max);
        $g = rand($min, $max);
        $b = rand($min, $max);
        if ($stringify) {
            return "$r,$g,$b";
        } else {
            return ['r' => $r, 'g' => $g, 'b' => $b];
        }
    }

    /**
     * 生成随即数字字符串.
     *
     * @param int $length 需要生成的字符串长度
     * @return string 处理结果
     */
    static function randomNumberString(int $length = 6)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= rand(0, 9);
        }
        return $result;
    }

    /**
     * 返回性别选择.
     *
     * @param bool $hasKeys 返回结果是否需要键
     * @return string[] 处理结果
     */
    static function genders(bool $hasKeys = false)
    {
        if ($hasKeys) {
            return ['男' => '男', '女' => '女', '无' => '无'];
        } else {
            return ['男', '女', '无'];
        }
    }

    /**
     * 返回HTTP请求方法.
     *
     * @param bool $hasKeys 返回的结果是否需要键
     * @return string[] 处理结果
     */
    static function httpMethods(bool $hasKeys = false)
    {
        if ($hasKeys) {
            return [
                'GET' => 'GET',
                'POST' => 'POST',
                'PUT' => 'PUT',
                'DELETE' => 'DELETE',
                'OPTION' => 'OPTION'
            ];
        } else {
            return ['GET', 'POST', 'PUT', 'DELETE', 'OPTION'];
        }
    }

    /**
     * 返回是或否.
     *
     * @param bool $hasKeys 返回的结果是否需要键
     * @return string[] 处理结果
     */
    static function yesOrNo(bool $hasKeys = false)
    {
        if ($hasKeys) {
            return [
                '是' => '是',
                '否' => '否',
            ];
        } else {
            return ['是', '否'];
        }
    }

    /**
     * 返回时间周期.
     *
     * @param bool $hasKeys 返回的结果是否需要键
     * @return string[] 处理结果
     */
    static function dateCycles(bool $hasKeys)
    {
        if ($hasKeys) {
            return [
                '天' => '天',
                '周' => '周',
                '月' => '月',
                '季' => '季',
                '年' => '年'
            ];
        } else {
            return ['天', '周', '月', '季', '年'];
        }
    }

    /**
     * 状态是否禁用.
     *
     * @return string[] 0是正常，1是禁用
     */
    static function disabled()
    {
        return ['正常', '禁用'];
    }

    /**
     * 通用的接口返回构造数组.
     * @param int $code
     * @param $message
     * @param $data
     * @param bool $array
     * @return array
     */
    static function response(int $code, $message, $data = null, bool $array = false)
    {
        $return = [];
        $return['code'] = $code;
        if ($message instanceof Exception) {
            $return['code'] = 500;
            $data = $message->getTrace();
            $message = $message->getFile() . ':' . $message->getLine() . ' ' . $message->getMessage();
        }
        $return['message'] = $message;
        $return['data'] = $data;

        if ($array) {
            return $return;
        }

        return response()->json($return)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}