<?php

namespace Core;

class Kernel
{
    private Router $router;
    private SecurityComponent $securityComponent;

    public function __construct()
    {
        $this->router = new Router();
        $this->securityComponent = new SecurityComponent();
    }

    /**
     * @return string|bool
     */
    public function handle(): string|bool
    {
        [
            'controller' => $controller,
            'method' => $method,
            'methodParams' => $methodParams
        ] = $this->router->getRoute();

        $result = $controller->$method(...$methodParams);
        return json_encode($result);
    }
}