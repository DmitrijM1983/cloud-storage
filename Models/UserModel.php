<?php

namespace Models;

use Core\SecurityComponent;

class UserModel
{
    private int $id = 0;
    private int $gender = 0;
    private int $age = 0;
    private string $email = '';
    private string $hashedPassword = '';
    private string $role = 'user';

    /**
     * @param string $email
     * @param int $gender
     * @param int $age
     * @param string $role
     * @return static
     */
    public static function create(string $email,  int $gender, int $age, string $role): self
    {
        $model = new self();
        $model->email = $email;
        $model->gender = $gender;
        $model->age = $age;
        $model->role = $role;
        return $model;
    }

    /**
     * @param string $email
     * @param int $gender
     * @param int $age
     * @param string $role
     * @return $this
     */
    public function update(string $email, int $gender, int $age, string $role): self
    {
        $this->email = $email;
        $this->gender = $gender;
        $this->age = $age;
        $this->role = $role;
        return $this;
    }

    /**
     * @param int $id
     * @return UserModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $email
     * @return UserModel
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param int $gender
     * @return UserModel
     */
    public function setGender(int $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @param int $age
     * @return UserModel
     */
    public function setAge(int $age): self
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @param string $role
     * @return UserModel
     */
    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string
     */
    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    /**
     * @param string $password
     * @return UserModel
     */
    public function setHashedPassword(string $password): self
    {
        $this->hashedPassword = SecurityComponent::hashPassword($password);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'gender' => $this->gender,
            'age' => $this->age,
            'role' => $this->role
        ];
    }
}