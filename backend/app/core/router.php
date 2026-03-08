<?php

class Router
{

    public function dispatch(): void
    {
        
        $url = $_GET['url'] ?? '';
        $url = trim($url, '/');

        $segments = explode('/', $url);

        // MODULO
        $module = !empty($segments[0]) ? strtolower($segments[0]) : Config::$defaultModule;

        // CONTROLADOR
        $controllerSegment = $segments[1] ?? Config::$defaultController;
        $controllerName = ucfirst($controllerSegment)."Controller";

        // METODO
        $method = $segments[2] ?? Config::$defaultMethod;

        // PARAMETROS
        $params = array_slice($segments, 3);

        // RUTA CONTROLADOR
        $controllerFile = "app/modules/" . strtolower($module) . "/controllers/" . $controllerName . ".php";

        if (!file_exists($controllerFile))
        {
            die("Archivo no existe: " . $controllerFile);
        }

        require_once $controllerFile;

        if (!class_exists($controllerName))
        {
            die("Clase no existe: " . $controllerName);
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $method))
        {
            die("Metodo no existe: " . $method);
        }

        call_user_func_array([$controller, $method], $params);

    }

}