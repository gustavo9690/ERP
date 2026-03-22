<?php

class Router
{
    public function dispatch(): void
    {
        try {
            $url = $_GET['url'] ?? '';
            $url = trim($url, '/');
            $segments = $url !== '' ? explode('/', $url) : [];

            // MÓDULO
            $module = !empty($segments[0]) ? strtolower($segments[0]) : Config::$defaultModule;

            // SUBMÓDULO
            $submodule = !empty($segments[1]) ? strtolower($segments[1]) : Config::$defaultSubmodule;

            // CONTROLADOR
            $controllerSegment = !empty($segments[2]) ? $segments[2] : Config::$defaultController;
            $controllerName = $this->formatControllerName($controllerSegment);

            // MÉTODO
            $method = !empty($segments[3]) ? $segments[3] : Config::$defaultMethod;

            // PARÁMETROS
            $params = array_slice($segments, 4);

            // ARCHIVO CONTROLADOR
            $controllerFile =
                "app/modules/" .
                $module . "/" .
                $submodule .
                "/controllers/" .
                $controllerName . ".php";

            if (!file_exists($controllerFile)) {
                Response::notFound("Controlador no encontrado: {$controllerName}");
            }

            require_once $controllerFile;

            if (!class_exists($controllerName)) {
                Response::serverError("Clase no encontrada: {$controllerName}");
            }

            $controller = new $controllerName();

            if (!method_exists($controller, $method)) {
                Response::notFound("Método no encontrado: {$method}");
            }

            call_user_func_array([$controller, $method], $params);

        } catch (Throwable $e) {
            Response::serverError($e->getMessage());
        }
    }

    private function formatControllerName(string $segment): string
    {
        $segment = str_replace(['-', '_'], ' ', strtolower($segment));
        $segment = str_replace(' ', '', ucwords($segment));

        return $segment . 'Controller';
    }
}