<?php


namespace Ace;


use Carbon\Carbon;

class DateTime
{
    /**
     * 秒转换为00:00:00格式.
     *
     * @param int $seconds 秒
     * @return string 00:00:00格式的时间
     */
    static function secondsToMinutes(int $seconds = 0)
    {
        $hour = floor($seconds / 60);
        $seconds = $seconds % 60;
        $hour = (strlen($hour) == 1) ? '0' . $hour : $hour;
        $seconds = (strlen($seconds) == 1) ? '0' . $seconds : $seconds;
        return $hour . ':' . $seconds;
    }

    /**
     * 时间戳返回周几.
     *
     * @param int $timestamp 需要转换的时间戳
     * @param string $lang 语言，zh_CN为默认中文简体，其它为英文
     * @return array|string 周几
     */
    static function dayOfWeek(int $timestamp, string $lang = 'zh_CN')
    {
        $day = (new Carbon($timestamp))->dayOfWeek;
        return self::week($lang, $day);
    }

    /**
     * 返回一周.
     *
     * @param string $lang 语言，zh_CN为默认中文简体，其它为英文
     * @param int|null $index 一周内的第几天，注意0为周日
     * @return array|string 如果没传入index，返回的是一周的数组，反之是index对应的第几天
     */
    static function week(string $lang = 'zh_CN', int $index = null)
    {
        $weekOfChinese = ['周日', '周一', '周二', '周三', '周四', '周五', '周六'];
        $weekOfEnglish = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $week = $weekOfEnglish;
        if ($lang == 'zh_CN') {
            $week = $weekOfChinese;
        }
        if ($index !== null) {
            return $week[$index];
        }
        return $week;
    }

    /**
     * 获取当前毫秒级时间
     * @return int 毫秒级时间
     */
    static function ms()
    {
        list($ms, $seconds) = explode(' ', microtime());
        $ms_time = (float)sprintf('%.0f', (floatval($ms) + floatval($seconds)) * 1000);
        return (int)$ms_time;
    }
}