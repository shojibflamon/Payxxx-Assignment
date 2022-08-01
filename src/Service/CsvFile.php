<?php

namespace Shojibflamon\PayseraAssignment\Service;

class CsvFile implements FileInterface
{
    private string $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getData(): FileData
    {
        return new FileData(file($this->file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
    }

}