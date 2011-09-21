<?php
class Fwk_Session
{
	public function __construct()
	{
		session_start();
	}

	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public function remove($key)
	{
		unset($_SESSION[$key]);
	}

	public function get($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}

		return null;
	}
}
