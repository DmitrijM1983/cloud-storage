<?php

namespace  Controllers;

use Core\Exceptions\FileError;
use JetBrains\PhpStorm\NoReturn;
use Service\FileService;
use Models\FileModel;

class FileController extends BaseController
{
    private ?FileService $fileService = null;

    /**
     * @return FileModel[]
     */
    public function listFiles(): array
    {
        /** @var FileModel[] $fileModels */
        $fileModels = $this->getFileService()->getList();
        $response = [];
        foreach ($fileModels as $model) {
            $response[] = $model->toArray();
        }
        return $response;
    }

    /**
     * @param int $id
     * @return array
     */
    public function fileInfo(int $id): array
    {
        return $this->getFileService()->getFile($id)->toArray();
    }

    /**
     * @return array|bool
     * @throws FileError
     */
    public function addFile(): array|bool
    {
        $data = $this->getDataFiles();
        if ($data['size']  > 2000000000) {
            throw new FileError('Размер файла превышает 2 гигабайта!');
        }
        return $this->getFileService()->addNewFile($data['name'], $data['tmp']);
    }

    /**
     * @param int $id
     * @return array
     */
    public function renameFile(int $id): array
    {
        return $this->getFileService()->rename($id, $this->getRequestData())->toArray();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteFile(int $id): void
    {
        $this->getFileService()->delete($id);
    }

    /**
     * @return bool
     */
    public function addDirectory(): bool
    {
        return $this->getFileService()->addDirectory($this->getRequestData()['directory_name']);
    }

    /**
     * @param int $id
     * @return void
     */
    #[NoReturn] public function renameDirectory(int $id): void
    {
        $this->getFileService()->renameDirectory($id, $this->getRequestData());
    }

    /**
     * @param int $id
     * @return array
     */
    public function directoryInfo(int $id): array
    {
        return $this->getFileService()->getDirectoryInfo($id);
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteDirectory(int $id): void
    {
        $this->getFileService()->deleteDir($id);
    }


    public function getShareUsersList(int $fileId): array
    {
        return $this->getFileService()->getShareUsers($fileId);
    }

    /**
     * @param int $fileId
     * @param int $userId
     * @return array
     */
    public function addShareUserId(int $fileId, int $userId): array
    {
         return $this->getFileService()->addShareUserId($fileId, $userId);
    }

    /**
     * @param int $fileId
     * @param int $userId
     * @return array
     */
    public function removeShareUserId(int $fileId, int $userId): array
    {
        return $this->getFileService()->removeShareUserId($fileId, $userId);
    }

    /**
     * @return FileService|null
     */
    private function getFileService(): ?FileService
    {
        if ($this->fileService === null) {
            $this->fileService = new FileService();
        }
        return $this->fileService;
    }
}