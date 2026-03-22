<?php

class App
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();

        // 🔹 Configuración inicial del sistema
        $this->init();
    }

    /* =====================================================
       INICIALIZACIÓN
    ===================================================== */

    private function init(): void
    {
        // Aquí puedes cargar configs, timezone, etc.

        date_default_timezone_set('America/Lima');

        // Manejo global de errores PHP
        set_exception_handler([$this, 'handleException']);
    }

    /* =====================================================
       EJECUCIÓN
    ===================================================== */

    public function run(): void
    {
        try {
            $this->router->dispatch();
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /* =====================================================
       MANEJO GLOBAL DE ERRORES
    ===================================================== */

    public function handleException(Throwable $e): void
    {
        Response::serverError($e->getMessage());
    }
}