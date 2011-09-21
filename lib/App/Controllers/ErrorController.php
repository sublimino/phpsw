<?php
class App_Controllers_ErrorController extends Fwk_Controller
{
	public function errorAction(Fwk_Request $request, $exception)
	{
        $responseCode = 500;

		$twig = $this->container->twig;

        if ($exception instanceof Fwk_RouteMatcherRouteNotFoundException) {
            $responseCode = 404;

			return new Fwk_Response($responseCode, $twig->render('404.html', array()));
        }

		$t = array();

		foreach ($exception->getTrace() as $trace) {
			if (isset($trace['class'])) {
				$definition = $trace['class'] . '->' . $trace['function'] . '(';
			} else {
				$definition = $trace['function'] . '(';
			}

			$count = 0;

			foreach ($trace['args'] as $arg) {
				if (is_object($arg)) {
					$definition .= 'Object(' . get_class($arg) . ')';
				} elseif (is_array($arg)) {
					$definition .= $arg;
				} elseif (is_numeric($arg)) {
					$definition .= $arg;
				} else {
					$definition .= '\'' . $arg . '\'';
				}

				if (++$count < count($trace['args'])) {
					$definition .= ', ';
				}
			}

			$definition .= ')';

			$trace['definition'] = $definition;

			$t[] = $trace;
		}	

		$class = get_class($exception);

		$debug = false;

		return new Fwk_Response($responseCode, $twig->render('exception.html', array(
			'exception' => $exception, 
			'trace' => $t, 
			'class' => $class,
			'debug' => $debug
		)));
	}
}
