<?php

namespace Shojibflamon\PayseraAssignment\Model;

class User
{
    public const USER_TYPE_PRIVATE = 'private';

    public const USER_TYPE_BUSINESS = 'business';

    /**
     * @var string
     */
    private string $userId;

    /**
     * @var string
     */
    private string $userType;

    /**
     * @param $userId
     * @param $userType
     */
    public function __construct($userId, $userType)
    {
        $this->userId = $userId;
        $this->userType = $userType;
    }

    /**
     * @return String
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return String
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

}