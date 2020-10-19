<?php

namespace App\Repositories\Users;

use App\User;

/**
 * Class UserRepository
 * @package App\Repositories\Users
 */
class UserRepository
{
    /**
     * @var User
     */
    protected $model;

    /**
     * DomainRepository constructor.
     * @param User $model
     */
    public function __construct(User $model)
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
