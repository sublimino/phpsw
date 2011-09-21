<?php
class Fwk_Response
{
    public function __construct($responseCode, $body)
    {
		switch ($responseCode) {
			case 500:
				header('HTTP/1.1 500 Internal Server Error');
				break;
			case 404:
				header('HTTP/1.1 404 Not Found');
				break;
			default:
				header('HTTP/1.1 200 OK');
		}

        echo $body;
    }
}
