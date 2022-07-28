<?php

namespace Shojibflamon\PayseraAssignment\Helper;

Class Dump
{
    public static function ddd($data)
    {
        self::dd($data);
        die();
    }

    public static function dd($data)
    {
        echo '<pre>', print_r($data, true), '</pre>';
    }
}