<?php

namespace Shojibflamon\PayseraAssignment\Model;

class UserType
{
    private $userType;

    public function __construct($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }



}