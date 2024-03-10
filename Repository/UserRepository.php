<?php

namespace Repository;

use Models\UserModel;
use PDO;


class UserRepository extends BaseRepository
{
    /**
     * @return UserModel[]
     */
    public function getList(): array
    {
        $sql = "SELECT * FROM `users` ";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $usersData = $statement->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($usersData as $userData) {
            $userModel = UserModel::create($userData['email'], $userData['gender'], $userData['age'], $userData['role']);
            $userModel->setId($userData['id']);
            $result[] = $userModel;
        }
        return $result;
    }

    /**
     * @param int $id
     * @return UserModel
     */
    public function getById(int $id): UserModel
    {
        $sql = "SELECT * FROM `users` where `id` = {$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $userData = $statement->fetch(PDO::FETCH_ASSOC);
        $userModel = UserModel::create($userData['email'], $userData['gender'], $userData['age'], $userData['role']);
        $userModel->setId($userData['id']);
        return $userModel;
    }

    /**
     * @param UserModel $user
     * @return bool
     */
    public function save(UserModel $user): bool
    {
        if ($user->getId() > 0) {
            $sql = "UPDATE `users` SET
                `email`='{$user->getEmail()}',
                `role`='{$user->getRole()}',
                `gender`='{$user->getGender()}',
                `age`='{$user->getAge()}'
                WHERE `id` = {$user->getId()}";
        } else {
            $sql = "INSERT INTO `users` (email, password, role) 
                    VALUES ('{$user->getEmail()}', '{$user->getHashedPassword()}', '{$user->getRole()}')";
        }
        $statement = $this->pdo->prepare($sql, [$user->getEmail(), $user->getHashedPassword(), $user->getRole()]);
        return $statement->execute();
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $sql = "DELETE FROM `users` WHERE `id` = {$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    /**
     * @param string $email
     * @return UserModel|null
     */
    public function getByEmail(string $email): ?UserModel
    {
        $sql = "SELECT * FROM `users` WHERE `email` = '{$email}'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result === false ? null : $this->mapToUserModel($result);
    }

    /**
     * @param array $data
     * @return UserModel
     */
    private function mapToUserModel(array $data): UserModel
    {
        $user = new UserModel();
        $user->setId($data['id']);
        $user->setEmail($data['email']);
        $user->setAge($data['age']);
        $user->setRole($data['role']);
        $user->setGender($data['gender']);
        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @return UserModel|null
     */
    public function getByAuthData(string $email, string $password): ?UserModel
    {
        $sql = "SELECT * FROM `users` WHERE `email` = '{$email}'";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            return $this->mapToUserModel($user);
        }
        return null;
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function updatePassword(string $email, string $password): bool
    {
        $sql = "UPDATE `users` SET `password` = '{$password}' WHERE `email` = '{$email}'";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute();
    }
}
