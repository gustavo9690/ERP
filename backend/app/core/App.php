<?php

class App
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->init();
    }

    private function init(): void
    {
        // ✅ Toda la configuración pasa por tu clase Config
        Config::init();

        // Manejadores globales
        set_exception_handler([$this, 'handleException']);
        set_error_handler(function ($severidad, $mensaje, $archivo, $linea) {
            throw new ErrorException($mensaje, 0, $severidad, $archivo, $linea);
        });
    }

    public function run(): void
    {
        try {
            $this->router->dispatch();
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    public function handleException(Throwable $e): void
    {
        if (Config::$env === 'development') {
            Response::serverError($e->getMessage(), [
                'archivo' => $e->getFile(),
                'linea'   => $e->getLine(),
                'codigo'  => $e->getCode()
            ]);
        } else {
            Response::serverError('Error interno del servidor');
        }
    }
}