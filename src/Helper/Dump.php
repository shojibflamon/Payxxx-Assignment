<?php

namespace Shojibflamon\PayxxxxAssignment\Helper;

trait Dump
{
    /**
     * @param $data
     * @return void
     */
    public static function ddd($data): void
    {
        self::dd($data);
        die();
    }

    /**
     * @param $data
     * @return void
     */
    public static function dd($data): void
    {
        echo '<pre>', print_r($data, true), '</pre>';
    }
}