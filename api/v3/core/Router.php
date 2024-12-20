<?php

class Router {
    private $routes;
    private $params;

    // Permite inicializar la clase router con parametros o por defecto
    public function __construct($routes = [], $params = []) {
        $this->routes = $routes;
        $this->params = $params;
    }
    
    public function getRoutes() {
        return $this->routes;
    }
    
    public function getParams() {
        return $this->params;
    }
    
    public function add($route, $params) {
        $this->routes[$route] = $params;
    }

    public function matchRoute($url) {
        /* echo "URL que llega matchRoutes = " . print_r($url) . '<br>' . '<br>'; */
        foreach($this->routes as $route=>$params) {
            $pattern = str_replace(['{id}', '/'], ['([0-9]+)','\/'], $route);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $url['path'])) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
}