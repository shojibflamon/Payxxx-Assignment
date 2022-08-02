<?php

namespace Shojibflamon\PayxxxxAssignment\Service;

class FileData
{
    /**
     * @var array
     */
    private array $data;

    /**
     * @param $data
     */
    public function __construct($data)
    {
        $this->data[] = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}