<?php

namespace Shojibflamon\PayseraAssignment\Helper;

trait Dump
{
    /**
     * @param $data
     * @return void
     */
    public static function ddd($data)
    {
        self::dd($data);
        die();
    }

    /**
     * @param $data
     * @return void
     */
    public static function dd($data)
    {
        echo '<pre>', print_r($data, true), '</pre>';
    }
}