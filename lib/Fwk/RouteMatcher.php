<?php
class Fwk_RouteMatcher
{
    protected $routes = array();

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    public function match($path)
    {
        foreach ($this->routes as $route => $info) {
            if (preg_match('&^' . $route . '$&', $path, $matches)) {
                array_shift($matches);

                if (count($info['params']) > 0) {
                    $info['params'] = array_combine(array_keys($info['params']), $matches);
                }

                return $info;
            }
        }

        throw new Fwk_RouteMatcherRouteNotFoundException(sprintf('"%s" not found', $path));
    }
}
