<?php

namespace Shojibflamon\PayseraAssignment\Helper;

trait Dump
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