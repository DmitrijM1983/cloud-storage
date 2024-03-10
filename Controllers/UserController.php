<?php

namespace Controllers;

use Core\Exceptions\AuthError;
use Models\UserModel;
use Service\AuthService;
use Service\UserService;


class UserController extends BaseController
{
    private ?UserService $userService = null;
    private ?AuthService $authService = null;

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
     *
     * @param int $id
     * @return array
     */
    public function showUser(int $id): array
    {
        return $this->getUserService()->getUser($id)->toArray();
    }

    /**
     *
     * @param int $id
     * @return array
     */
    public function updateUser(int $id): array
    {
        return $this->getUserService()->updateUser($id, $this->getRequestData())->toArray();
    }

    /**
     * @return array
     */
    public function registration(): array
    {
        $data = $this->getRequestData();
        return $this->getAuthService()->registration($data['email'], $data['password'])->toArray();
    }

    /**
     *
     * @return array
     */
    public function login(): array
    {
        $data = $this->getRequestData();
        try {
            return $this->getAuthService()->login($data['email'], $data['password'])->toArray();
        } catch (AuthError $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    /**
     * @return bool
     */
    public function logout(): bool
    {
        return $this->getAuthService()->logout();
    }

    /**
     * @return bool
     * @throws AuthError
     */
    public function resetPassword(): bool
    {
        $data = $this->getRequestData();
        return $this->getAuthService()->reset($data['email']);
    }

    /**
     * @return bool
     */
    public function updatePassword(): bool
    {
        $data = $this->getRequestData();
        return $this->getAuthService()->setNewPassword($data['email'], $data['password']);
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

    /**
     * @return AuthService
     */
    private function getAuthService(): AuthService
    {
        if ($this->authService === null) {
            $this->authService = new AuthService();
        }
        return $this->authService;
    }
}