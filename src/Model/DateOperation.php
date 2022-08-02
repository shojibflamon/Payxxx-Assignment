<?php

namespace Shojibflamon\PayxxxxAssignment\Model;

class DateOperation
{
    /**
     * @var
     */
    private $date;

    /**
     * @param $date
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getFirstDayOfWeek()
    {
        return date('Y-m-d', strtotime("this week", strtotime($this->date)));
    }
}