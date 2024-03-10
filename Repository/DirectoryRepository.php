<?php

namespace Repository;

use PDO;

class DirectoryRepository extends BaseRepository
{
    /**
     * @param string $directoryName
     * @return bool
     */
    public function saveDirectory(string $directoryName): bool
    {
        $sql = "INSERT INTO `directories` (directory_name) VALUES ('{$directoryName}')";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public  function getDirectory(int $id): mixed
    {
        $sql = "SELECT * FROM `directories` WHERE `id` = {$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @param string $directoryName
     * @return void
     */
    public function updateDirectory(int $id, string $directoryName): void
    {
        $sql = "UPDATE `directories` SET `directory_name` = '{$directoryName}' WHERE `id` = {$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteDirectory(int $id): void
    {
        $sql = "DELETE FROM `directories` WHERE `id` = {$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }
}