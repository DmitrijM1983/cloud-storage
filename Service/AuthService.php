<?php

namespace Service;

use Core\Exceptions\AuthError;
use Models\UserModel;
use Repository\UserRepository;


class AuthService
{
    private ?UserRepository $userRepository = null;

    /**
     * @param string $email
     * @param string $password
     * @return UserModel
     */
    public function registration(string $email, string $password): UserModel
    {
        $user = new UserModel();
        $user->setEmail($email);
        $user->setHashedPassword($password);
        $this->getUserRepository()->save($user);
        return $this->getUserRepository()->getByEmail($email);
    }

    /**
     * @param string $email
     * @param string $password
     * @return UserModel
     * @throws AuthError
     */
    public function login(string $email, string $password): UserModel
    {
        $user = $this->getUserRepository()->getByAuthData($email, $password);
        if ($user === null) {
            throw new AuthError('Пользователь не найден!');
        }
        setcookie('auth_user', $user->getId(), time() + 604800);
        return $user;
    }

    public function logout(): bool
    {
        return session_destroy();
    }

    /**
     * @param string $email
     * @return bool
     * @throws AuthError
     */
    public function reset(string $email): bool
    {
        $user = $this->getUserRepository()->getByEmail($email);
        if ($user === null) {
            throw new AuthError('Пользователь не найден!');
        }
        $key = md5('password' . rand(1000, 9999));
        $url = '/password_update?key=' . $key;
        return mail($email, 'Восстановление пароля', "Для восстановления пароля перейдите по ссылке {$url}");
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function setNewPassword(string $email, string $password): bool
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        return $this->getUserRepository()->updatePassword($email, $password);
    }

    /**
     * @return UserRepository
     */
    private function getUserRepository(): UserRepository
    {
        if ($this->userRepository === null) {
            $this->userRepository = new UserRepository();
        }
        return $this->userRepository;
    }
}