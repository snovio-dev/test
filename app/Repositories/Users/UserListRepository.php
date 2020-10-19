<?php

namespace App\Repositories\Users;

use App\UserList;

/**
 * Class UserListRepository
 * @package App\Repositories\Users
 */
class UserListRepository
{
    /**
     * @var UserList
     */
    protected $model;

    /**
     * DomainRepository constructor.
     * @param UserList $model
     */
    public function __construct(UserList $model)
    {
        $this->model = $model;
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function firstOrNew($id = null)
    {
        return $this->model::firstOrNew($id);
    }
}
