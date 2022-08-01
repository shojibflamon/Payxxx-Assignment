<?php

namespace Shojibflamon\PayseraAssignment\Service;

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