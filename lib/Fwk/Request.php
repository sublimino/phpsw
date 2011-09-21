<?php
class Fwk_Request
{
    protected $params = array();    
	protected $pathInfo = '';
	protected $server = array();
	protected $post = array();
	protected $get = array();

	public function __construct($server, $post, $get)
	{
		$this->server = $server;
		$this->post = $post;
		$this->get = $get;

		if (isset($server['PATH_INFO'])) {
			$this->pathInfo = $server['PATH_INFO'];
		}
	}

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParam($param)
    {
        if (!isset($this->params[$param])) {
            return null;
        }

        return $this->params[$param];
    }

    public function getParams()
    {
        return $this->params;
    }

	public function getPathInfo()
	{
		return $this->pathInfo;
	}

	public function get($key)
	{
		if (isset($this->post[$key])) {
			return $this->post[$key];
		}

		if (isset($this->get[$key])) {
			return $this->get[$key];
		}

		return null;
	}

	public function isPost()
	{
		return $this->server['REQUEST_METHOD'] == 'POST';
	}

	public function getHttpHost()
	{
		return 'http://' . $this->server['SERVER_NAME'];
	}

	public function getRequestUri()
	{
		if (strpos($this->server['REQUEST_URI'], '?') !== false) {
			return substr($this->server['REQUEST_URI'], 0, strpos($this->server['REQUEST_URI'], '?'));
		}

		return $this->server['REQUEST_URI'];
	}
}
