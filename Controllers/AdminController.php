<?php

namespace Controllers;

use Models\UserModel;
use Service\UserService;

class AdminController extends BaseController
{
    private ?UserService $userService = null;

    /**
     * @return UserModel[]
     */
    public function usersList(): array
    {
        /** @var UserModel[] $userModels */
        $userModels = $this->getUserService()->getUsersList();
        $response = [];
        foreach ($userModels as $model) {
            $response[] = $model->toArray();
        }
        return $response;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function showUser(int $id): array
    {
        return $this->getUserService()->getUser($id)->toArray();
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function updateUser(int $id): array
    {
        return $this->getUserService()->updateUser($id, $this->getRequestData())->toArray();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        $this->getUserService()->deleteUser($id);
    }

    /**
     * @return UserService
     */
    private function getUserService(): UserService
    {
        if ($this->userService === null) {
            $this->userService = new UserService();
        }
        return $this->userService;
    }
}