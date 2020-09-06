<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\NotAllowDomainException;
use App\Exceptions\UserListSaveException;
use App\Exceptions\UserRegistrationException;
use App\Exceptions\UserSaveException;
use App\User;
use App\UserList;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Psr\Log\LoggerInterface;
use Throwable;

class UserRegistrationService
{
    private $domainService;
    private $mailService;
    private $logger;

    /**
     * UserRegistrationService constructor.
     *
     * @param DomainService $domainService
     * @param MailService $mailService
     * @param Logger $logger
     */
    public function __construct(DomainService $domainService, MailService $mailService, LoggerInterface $logger)
    {
        $this->domainService = $domainService;
        $this->mailService = $mailService;
        $this->logger = $logger;
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws UserRegistrationException
     */
    public function register(array $data): bool
    {
        DB::beginTransaction();

        try {
            if (!$this->domainService->checkAllowIpAndDomain($data['email'])) {
                throw new NotAllowDomainException('Not allow ip or domain');
            }

            $user = $this->saveUser($data);
            $this->saveUserList($user);
            $this->mailService->sendConfirmation($user);
            $this->addSuccessLog($data);
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            $this->addErrorLog($data);
            throw new UserRegistrationException($exception->getMessage(), $exception->getCode());
        }
        return true;
    }

    /**
     * @param array $data
     *
     * @return User
     * @throws UserSaveException
     */
    public function saveUser(array $data): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        if (!$user->save()) {
            throw new UserSaveException('User saving error');
        }
        return $user;
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws UserListSaveException
     */
    public function saveUserList(User $user): bool
    {
        $userList = new UserList();
        $userList->user_id = $user->id;
        $userList->name = 'First email addresses list';
        if (!$userList->save()) {
            throw new UserListSaveException('User\'s list saving error');
        }
        return true;
    }

    /**
     * @param array $data
     */
    private function addErrorLog(array $data): void
    {
        $this->logger->error(print_r($data, true));
    }

    /**
     * @param array $data
     */
    private function addSuccessLog(array $data): void
    {
        $this->logger->notice(print_r($data, true));
    }
}
