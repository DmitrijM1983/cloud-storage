<?php

namespace Core;

use Controllers\AdminController;
use Controllers\FileController;
use Controllers\UserController;

class Router
{
    private array $urlList = [
    '/users/list' => ['GET', [UserController::class, 'usersList']],
    '/users/get/{id}' => ['GET', [UserController::class, 'showUser']],
    '/users/update/{id}' => ['PUT', [UserController::class, 'updateUser']],
    '/registration' => ['POST', [UserController::class, 'registration']],
    '/login' => ['POST', [UserController::class, 'login']],
    '/logout' => ['GET', [UserController::class, 'logout']],
    '/password-reset' => ['POST', [UserController::class, 'resetPassword']],
    '/password-update' => ['PUT', [UserController::class, 'updatePassword']],
    '/admin/users/list' => ['GET', [AdminController::class, 'usersList']],
    '/admin/users/get/{id}' => ['GET', [AdminController::class, 'showUser']],
    '/admin/users/update/{id}' => ['PUT', [AdminController::class, 'updateUser']],
    '/admin/users/delete/{id}' => ['DELETE', [AdminController::class, 'deleteUser']],
    '/files/list' => ['GET', [FileController::class, 'listFiles']],
    '/files/get/{id}' => ['GET', [FileController::class, 'fileInfo']],
    '/files/add' => ['POST', [FileController::class, 'addFile']],
    '/files/rename/{id}' => ['PUT', [FileController::class, 'renameFile']],
    '/files/remove/{id}' => ['DELETE', [FileController::class, 'deleteFile']],
    '/directories/add' => ['POST', [FileController::class, 'addDirectory']],
    '/directories/rename/{id}' => ['PUT', [FileController::class, 'renameDirectory']],
    '/directories/get/{id}' => ['GET', [FileController::class, 'directoryInfo']],
    '/directories/delete/{id}' => ['DELETE', [FileController::class, 'deleteDirectory']],
    '/files/share/{id}' => ['GET', [FileController::class, 'getShareUsersList']],
    '/file/share/{id}/{user_id}' => ['PUT', [FileController::class, 'addShareUserId']],
    '/file/share/delete/{id}/{user_id}' => ['DELETE', [FileController::class, 'removeShareUserId']]
    ];


    public function getRoute(): ?array
    {
        $path = $_SERVER['REQUEST_URI'] ?? '';
        $pathRep = preg_replace('/(\d+)/', '{}', $path);
        foreach ($this->urlList as $url => $methodAction) {
            $urlRep = preg_replace('/{\w+}/', '{}', $url);
            if ($pathRep == $urlRep && $methodAction[0] == $_SERVER['REQUEST_METHOD']) {
                $params = [];
                $pathArray = explode('/', $path);
                foreach ($pathArray as $item) {
                    if (is_numeric($item)) {
                        $params[] = $item;
                    }
                }

                $controller = new $methodAction[1][0]();
                $method = $methodAction[1][1];
                $methodParams = [
                    $params[0] ?? $login ?? '',
                    $params[1] ?? $password ?? ''
                ];

                return [
                    'controller' => $controller,
                    'method' => $method,
                    'methodParams' => $methodParams
                ];
            }
        }
        return null;
    }
}