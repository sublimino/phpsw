<?php
class Fwk_App
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

    public function callAction($route, Fwk_Request $request)
    {
        if (!class_exists($route['controller'])) {
            throw new Exception(sprintf('%s does not exist', $route['controller']));
        }

        $controller = new $route['controller']($this->container);

        if (!method_exists($controller, $route['action'])) {
            throw new Exception(sprintf('%s::%s() does not exist', $route['controller'], $route['action']));
        }

        $request->setParams($route['params']);
        $controller->{$route['action']}($request);
    }

	public function handle(Fwk_Request $request)
	{
		$matcher = $this->container->routematcher;
        $route = $matcher->match($request->getRequestUri());
        $this->callAction($route, $request);
	}

    public function handleException($exception)
    {
		try {
			$controller = new App_Controllers_ErrorController($this->container);
			$controller->errorAction(new Fwk_Request(array(), array(), array()), $exception); 
		} catch (Exception $e) {
			die((string) $e);
		}
    }
}
