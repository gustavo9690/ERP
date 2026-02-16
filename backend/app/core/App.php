<?php

class App
{

    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run(): void
    {
        $this->router->dispatch();
    }

}
