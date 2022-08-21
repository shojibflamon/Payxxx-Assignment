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
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}