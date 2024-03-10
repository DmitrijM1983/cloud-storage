<?php

namespace Core;

use Models\UserModel;
use Repository\UserRepository;

class SecurityComponent
{
    private static ?UserModel $authUser;

    public function __construct()
    {
        $this->init();
    }

    /**
     * @return void
     */
    private static function init(): void
    {
        $id = $_COOKIE['auth_user'] ?? null;
        if ($id === null) {
            return;
        }
        $user = (new UserRepository())->getById($id);
        self::$authUser = $user;
    }

    /**
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}