<?php
class Fwk_Autoloader
{
	public static function register()
	{
		spl_autoload_register(array('Fwk_Autoloader', 'autoload'));
	}

    public static function autoload($class)
    {
        if (class_exists($class) || interface_exists($class)) {
            return true;
        }

        $class = str_replace('_', '/', $class);

        $dirs = array(
            __DIR__ . '/..',
        );

        foreach ($dirs as $dir) {
            if (file_exists($dir . '/' . $class . '.php')) {
                require $dir . '/' . $class . '.php';
                return true;
            }
        }

        return false;
    }
}
