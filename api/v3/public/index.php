<?php

require '../core/Router.php';
require '../app/controllers/UserController.php';

$url = $_SERVER['QUERY_STRING'];
/* echo 'URL = ' . $url . '<br>' . '<br>'; */


/* ----- AÑADIR RUTAS VALIDAS ----- */

$router = new Router();

$router->add('/public/users/get', array(
    'controller'=> 'UserController',
    'action'=>'getAllUsers'
));

$router->add('/public/users/post', array(
    'controller'=> 'UserController',
    'action'=>'registerUser'
));

$router->add('/public/users/update', array(
    'controller'=> 'UserController',
    'action'=>'updateAllIds'
));

$router->add('/public/users/delete', array(
    'controller'=> 'UserController',
    'action'=>'deleteAllUsers'
));

$router->add('/public/users/get/{id}', array(
    'controller'=> 'UserController',
    'action'=>'getUserById'
));

$router->add('/public/users/update/{id}', array(
    'controller'=> 'UserController',
    'action'=>'updateUserById'
));

$router->add('/public/users/delete/{id}', array(
    'controller'=> 'UserController',
    'action'=>'deleteUserById'
));


/* ----- PARSEAR URL ----- */

$urlParams = explode('/', $url);
$urlArray = array(
    'HTTP'=>$_SERVER['REQUEST_METHOD'],
    'path'=>$url,
    'controller'=>'',
    'action'=>'',
    'params'=>''
);

if (!empty($urlParams[2])) {
    $urlArray['controller'] = ucwords($urlParams[2]);
    if (!empty($urlParams[3])) {
        $urlArray['action'] = $urlParams[3];
        if (!empty($urlParams[4])) {
            $urlArray['params'] = $urlParams[4];
        }
    } else {
        $urlArray['action'] = 'index';
    }
} else {
    $urlArray['controller'] = 'UserController';
    $urlArray['action'] = 'index';
}


/* ----- EJECUTAR CONTROLADOR Y METODO ----- */

if ($router->matchRoute($urlArray)) {
    $method = $_SERVER['REQUEST_METHOD'];
    $params = [];

    if ($method === 'GET') {
        $params[] = intval($urlArray['params']) ?? null;
    } else if ($method === 'POST') {
        $json = file_get_contents('php://input');
        $params[] = json_decode($json, true);
    } else if ($method === 'PUT') {
        $id = intval($urlArray['params']) ?? null;
        $json = file_get_contents('php://input');
        $params[] = $id;
        $params[] = json_decode($json, true);
    } else if ($method === 'DELETE') {
        $params[] = intval($urlArray['params']) ?? null;
    }

    $controller = $router->getParams()['controller'];
    $action = $router->getParams()['action'];
    
    $controller = new $controller();
    if (method_exists($controller, $action)) {
        $resp = call_user_func_array([$controller, $action], $params);
/*         echo json_encode(['message' => 'Request processed successfully', 'data' => $resp]);
 */    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Method not found']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}
