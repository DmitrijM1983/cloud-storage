<?php

namespace Repository;

use Models\FileModel;
use PDO;

class FileRepository extends BaseRepository
{

    /**
     * @return array
     */
    public function getFilesList(): array
    {
        $sql = "SELECT * FROM `files` ";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $filesData = $statement->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($filesData as $fileData) {
            $fileModel = fileModel::create($fileData['file_name'], json_decode($fileData['shared_users']));
            $fileModel->setId($fileData['id']);
            $result[] = $fileModel;
        }
        return $result;
    }

    /**
     * @param int $id
     * @return FileModel
     */
    public function getFileById(int $id): FileModel
    {
        $sql = "SELECT * FROM `files` where `id` = {$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $fileData = $statement->fetch(PDO::FETCH_ASSOC);

        if ($fileData['shared_users'] === null) {
            $fileModel = FileModel::create($fileData['file_name'], null);
            $fileModel->setId($fileData['id']);
            return $fileModel;
        }
        $fileModel = FileModel::create($fileData['file_name'], json_decode($fileData['shared_users']));
        $fileModel->setId($fileData['id']);
        return $fileModel;
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function saveFile(string $fileName): bool
    {
        $sql = "INSERT INTO `files` (file_name) VALUES ('{$fileName}')";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute();
    }

    /**
     * @param int $id
     * @param string $fileName
     * @return bool
     */
    public function renameFile(int $id, string $fileName): bool
    {
        $sql = "UPDATE `files` SET `file_name`='{$fileName}' WHERE `id`={$id}";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteFile(int $id): bool
    {
        $sql = "DELETE FROM `files` WHERE `id` = {$id}";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getShareUsersList(int $id): array
    {
        $sql = "SELECT `shared_users` FROM `files` WHERE `id` = {$id}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $sharedUsers = json_decode($statement->fetch(PDO::FETCH_ASSOC)['shared_users']);
        $sharedUsersList = [];
        foreach ($sharedUsers as $userId) {
            $sql = "SELECT `id`, `email`, `gender`, `age`, `role` FROM `users` WHERE `id` = {$userId}";
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            $sharedUsersList[] = $user;
        }
        return $sharedUsersList;
    }

    /**
     * @param int $fileId
     * @param int $userId
     * @return array
     */
    public function addUsersToFile(int $fileId, int $userId): array
    {
        $sql = "SELECT `shared_users` FROM `files` WHERE `id` = {$fileId}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $sharedUsers = json_decode($statement->fetch(PDO::FETCH_ASSOC)['shared_users']);
        $sharedUsers[] = $userId;
        $sharedUsers = json_encode($sharedUsers);

        $sql = "UPDATE `files` SET `shared_users` = '{$sharedUsers}' WHERE `id` = {$fileId}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $this->getShareUsersList($fileId);
    }

    /**
     * @param int $fileId
     * @param int $userId
     * @return array
     */
    public function removeUsersFromFile(int $fileId, int $userId): array
    {
        $sql = "SELECT `shared_users` FROM `files` WHERE `id` = {$fileId}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $sharedUsers = json_decode($statement->fetch(PDO::FETCH_ASSOC)['shared_users']);

        foreach ($sharedUsers as $key => $id) {
            if ($id === $userId) {
                unset($sharedUsers[$key]);
            }
        }
        $sharedUsers = json_encode($sharedUsers);

        $sql = "UPDATE `files` SET `shared_users` = '{$sharedUsers}' WHERE `id` = {$fileId}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        return $this->getShareUsersList($fileId);
    }
}