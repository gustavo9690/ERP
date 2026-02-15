<?php

class Router
{
    public function run()
    {
        $url = $_GET['url'] ?? '';

        $segments = explode('/', trim($url, '/'));

        $controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';

        $method = $segments[1] ?? 'index';

        $params = array_slice($segments, 2);

        $controllerFile = "app/modules/" . strtolower($segments[0]) . "/" . $controllerName . ".php";

        if (!file_exists($controllerFile)) {
            jsonResponse(["error" => "Controller no existe"], 404);
        }

        require_once $controllerFile;

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            jsonResponse(["error" => "Metodo no existe"], 404);
        }

        call_user_func_array([$controller, $method], $params);
    }
}
