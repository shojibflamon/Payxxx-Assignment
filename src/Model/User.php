<?php

namespace Shojibflamon\PayseraAssignment\Model;

class User
{
    public const USER_TYPE_PRIVATE = 'private';
    public const USER_TYPE_BUSINESS = 'business';

    private string $userId;
    private string $userType;

    public function __construct($userId,$userType)
    {
        $this->userId = $userId;
        $this->userType = $userType;
    }

    /**
     * @return String
     */
    public function getUserId(): String
    {
        return $this->userId;
    }

    /**
     * @return String
     */
    public function getUserType(): String
    {
        return $this->userType;
    }

}