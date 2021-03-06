<?php

class Route
{
    static function start()
    {
        $params = [];
        $controllerName = "main";
        $actionName = "index";

        $routes = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
        // Controller
        if (!empty($routes[0])) {
            $controllerName = ucfirst($routes[0]);
        }

        // Action
        if (!empty($routes[1])) {
            $actionName = $routes[1];
        }

        $modelName = $controllerName;
        $modelPath = "application/models/" . $controllerName . "Model.php";
        if (file_exists($modelPath)) {
            require_once "$modelPath";
        }


        $controllerPath = "application/controllers/" . $controllerName . "Controller.php";
        if (file_exists($controllerPath)) {
            require_once "$controllerPath";
        } else {
            Route::Error404();
        }

//        if (!empty($routes[2])) {
//         $param = $routes[2];
//        }

        if (!empty($routes[2])) {
            $keys = $values = [];
            for ($i = 2; $i < count($routes); $i++) {
                if ($i % 2 == 0) {
                    $keys[] = $routes[$i];
                } else {
                    $values[] = $routes[$i];
                }
            }
            $params = array_combine($keys, $values);
        }

//        print_r($routes);

        $controller = new $controllerName;
        $action = $actionName;

        if (method_exists($controller, $action)) {
            $controller->$action($params);
        } else {
            Route::Error404();
        }
    }

    function Error404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . 'C404');
    }
}
