<?php
class Fwk_Controller 
{
	protected $container;

    public function __construct($container)
    {
		$this->container = $container;
    }

	public function redirect($url)
	{
		header('Location: ' . $url);
	}
}
