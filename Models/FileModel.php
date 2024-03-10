<?php

namespace Models;

class FileModel
{
    public int $id;
    private string $fileName;
    public ?array $sharedUsers;

    /**
     * @param string $fileName
     * @param array|null $sharedUsers
     * @return static
     */
    public static function create(string $fileName, ?array $sharedUsers): self
    {
        $model = new self();
        $model->fileName = $fileName;
        $model->sharedUsers = $sharedUsers;
        return $model;
    }

    /**
     * @param int $id
     * @return FileModel
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return $this
     */
    public function updateFile(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fileName' => $this->fileName,
            'shared_users' => $this->sharedUsers
        ];
    }
}