<?php

namespace App\Services\Auth;

use App\Events\RegistredUserEvent;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\Users\UserListRepository;
use App\Repositories\Users\UserRepository;

/**
 * Class AuthService
 * @package App\Services\Auth
 */
class AuthService
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserListRepository
     */
    protected $userListRepository;

    /**
     * AuthService constructor.
     * @param UserRepository $repository
     * @param UserListRepository $userListRepository
     */
    public function __construct(UserRepository $repository, UserListRepository $userListRepository)
    {
        $this->repository = $repository;
        $this->userListRepository = $userListRepository;
    }

    /**
     * @param RegisterRequest $request
     * @return mixed|null
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->repository->firstOrNew();
            $user->fill($request->all());
            $user->save();

            event(new RegistredUserEvent($user));

            $userList = $this->userListRepository->firstOrNew();
            $userList->fill([
                'user_id' => $user->id,
                'name' => 'First email addresses list'
            ]);
            $userList->save();

            \Log::info('success', $request->all()->toArray());

            return $user;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }

        return null;
    }
}
