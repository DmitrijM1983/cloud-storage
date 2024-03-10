<?php

namespace Service;

use Models\UserModel;
use Repository\UserRepository;


class UserService
{
    public function __construct(private ?UserRepository $repository = null)
    {

    }

    /**
     * @return array
     */
    public function getUsersList(): array
    {
        return $this->getUserRepository()->getList();
    }

    /**
     * @param int $id
     * @return UserModel
     */
    public function getUser(int $id): UserModel
    {
        return $this->getUserRepository()->getById($id);
    }

    /**
     * @param int $id
     * @param array $userData
     * @return UserModel
     */
    public function updateUser(int $id, array $userData): UserModel
    {
        $user = $this->getUserRepository()->getById($id);
        if (!isset($userData['email'])) {
            $userData['email'] = $user->getEmail();
        }
        if (!isset($userData['gender'])) {
            $userData['gender'] = $user->getGender();
        }
        if (!isset($userData['age'])) {
            $userData['age'] = $user->getAge();
        }
        if (!isset($userData['role'])) {
            $userData['role'] = $user->getRole();
        }
        $user->update($userData['email'], $userData['gender'], $userData['age'], $userData['role']);
        $this->getUserRepository()->save($user);
        return $user;
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        $this->getUserRepository()->delete($id);
    }

    /**
     * @return UserRepository
     */
    private function getUserRepository(): UserRepository
    {
        if ($this->repository === null) {
            $this->repository = new UserRepository();
        }
        return $this->repository;
    }
}
