<?php

namespace Service;

use JetBrains\PhpStorm\NoReturn;
use Models\FileModel;
use Repository\DirectoryRepository;
use Repository\FileRepository;

class FileService
{
    public int $id;

    public function __construct(private ?FileRepository $fileRepository = null,
                                private ?DirectoryRepository $directoryRepository = null)
    {

    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->getFileRepository()->getFilesList();
    }

    /**
     * @param int $id
     * @return FileModel
     */
    public function getFile(int $id): FileModel
    {
        return $this->getFileRepository()->getFileById($id);
    }

    /**
     * @param string $name
     * @param string $tmp
     * @return bool
     */
    public function addNewFile(string $name, string  $tmp): bool
    {
        $file = 'files/' . uniqid() . '-' . $name;
        move_uploaded_file($tmp, $file);
        return $this->getFileRepository()->saveFile($file);
    }

    /**
     * @param int $id
     * @param array $fileData
     * @return FileModel
     */
    public function rename(int $id, array $fileData): FileModel
    {
        $file = $this->getFileRepository()->getFileById($id);
        $directory = explode('/', $file->getFileName())[0];
        rename($file->getFileName(), $directory . '/' . $fileData['file_name']);
        $newFile = $file->updateFile($directory . '/' . $fileData['file_name']);
        $this->getFileRepository()->renameFile($id, $newFile->getFileName());
        return $newFile;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $file = $this->getfileRepository()->getFileById($id);
        $fileName = $file->getFileName();
        unlink($fileName);
        return $this->fileRepository->deleteFile($id);
    }

    /**
     * @param string $directoryName
     * @return bool
     */
    public function addDirectory(string $directoryName): bool
    {
        if (file_exists("files/{$directoryName}")) {
            echo 'Папка с таким именем уже существует!';
        } else {
            mkdir("files/{$directoryName}", 0777);
        }
        return $this->getDirectoryRepository()->saveDirectory('files/' . $directoryName);
    }

    /**
     * @param int $id
     * @param array $directoryData
     * @return void
     */
    #[NoReturn] public function renameDirectory(int $id, array $directoryData): void
    {
        $directoryOldName = $this->getDirectoryInfo($id)['directory_name'];
        $directory = explode('/', $directoryOldName)[0];
        $this->getDirectoryRepository()->updateDirectory($id, $directory . '/' . $directoryData['directory_name']);
        rename($directoryOldName, $directory . '/' . $directoryData['directory_name']);
    }

    /**
     * @param int $id
     * @return array
     */
    public function getDirectoryInfo(int $id): array
    {
        $directoryName = $this->getDirectoryRepository()->getDirectory($id)['directory_name'];
        $directoryContent = scandir($directoryName);
        $directoryFiles = [];
        foreach ($directoryContent as $content) {
            if ($content === '.' || $content === '..') {
                continue;
            } else {
                $directoryFiles[] = $content;
            }
        }
        return [
            'directory_name' => $directoryName,
            'directory_files' => $directoryFiles
        ];
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteDir(int $id): void
    {
        $directoryName = $this->getDirectoryRepository()->getDirectory($id)['directory_name'];
        $filesInDirectory = scandir($directoryName);
        foreach ($filesInDirectory as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            } else {
                unlink($directoryName . '/' . $file);
            }
        }
        rmdir($directoryName);
        $this->getDirectoryRepository()->deleteDirectory($id);
    }

    /**
     * @param int $fileId
     * @return array
     */
    public function getShareUsers(int $fileId): array
    {
        return $this->getFileRepository()->getShareUsersList($fileId);
    }

    /**
     * @param int $fileId
     * @param int $userId
     * @return array
     */
    public function addShareUserId(int $fileId, int $userId): array
    {
        return $this->getFileRepository()->addUsersToFile($fileId, $userId);
    }

    /**
     * @param int $fileId
     * @param int $userId
     * @return array
     */
    public function removeShareUserId(int $fileId, int $userId): array
    {
        return $this->getFileRepository()->removeUsersFromFile($fileId, $userId);
    }

    /**
     * @return FileRepository
     */
    private function getFileRepository(): FileRepository
    {
        if ($this->fileRepository === null) {
            $this->fileRepository = new FileRepository();
        }
        return $this->fileRepository;

    }

    /**
     * @return DirectoryRepository
     */
    private function getDirectoryRepository(): DirectoryRepository
    {
        if ($this->directoryRepository === null) {
            $this->directoryRepository = new DirectoryRepository();
        }
        return $this->directoryRepository;
    }
}
