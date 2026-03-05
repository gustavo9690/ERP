<?php

class Router
{

    public function dispatch(): void
    {
        
        $url = $_GET['url'] ?? '';
       
        $url = trim($url, '/');

        $segments = explode('/', $url);

        // CONTROLADOR
        $controllerSegment = $segments[0] ?? '';
        $controllerName = !empty($controllerSegment)?ucfirst($controllerSegment):Config::$defaultController;
        // METODO
        $method = $segments[1] ?? Config::$defaultMethod;

        // PARAMETROS
        $params = array_slice($segments, 2);

        // RUTA ARCHIVO CONTROLADOR
        $controllerFile = "app/modules/" . strtolower($controllerName) . "/controllers/" . $controllerName . ".php";

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
