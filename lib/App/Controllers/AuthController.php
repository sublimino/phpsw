<?php
class App_Controllers_AuthController extends Fwk_Controller
{
	public function loginAction(Fwk_Request $request)
	{
		$session = $this->container->session;	

		if (null !== ($id = $session->get('id'))) {
			return $this->redirect('/');
		}

		$oauth = new OAuth(CONS_KEY, CONS_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
		$oauth->enableDebug();

		$request_token_info = $oauth->getRequestToken('https://twitter.com/oauth/request_token', $request->getHttpHost() . '/auth');

		$rec = array();
		$rec['token'] = $request_token_info['oauth_token'];
		$rec['secret'] = $request_token_info['oauth_token_secret'];

		$session->set('foo', $rec['secret']);

		return $this->redirect('https://twitter.com/oauth/authenticate?oauth_token='.$rec['token']);
	}

	public function logoutAction(Fwk_Request $request)
	{
		$session = $this->container->session;	
		$session->remove('user');

		return $this->redirect('/');
	}

	public function authAction(Fwk_Request $request)
	{
		$session = $this->container->session;	

		if (null !== ($id = $session->get('id'))) {
			return $app->redirect('/');
		}

		$oauth_token = $request->get('oauth_token');

		if ($oauth_token == null) {
			return new Fwk_Response(500, 'Invalid request');
		}

		$secret = $session->get('foo');

		$oauth = new OAuth(CONS_KEY, CONS_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
		$oauth->enableDebug();

		$oauth->setToken($oauth_token, $secret);
		
		try {
			$oauth_token_info = $oauth->getAccessToken('https://twitter.com/oauth/access_token');
		} catch (OAuthException $e) {
			return new Fwk_Response(500, $e->getMessage());
		}

		$oauth->setToken($oauth_token_info['oauth_token'], $oauth_token_info['oauth_token_secret']);
		$oauth->fetch('https://twitter.com/account/verify_credentials.json'); 
		$json = json_decode($oauth->getLastResponse());

		$em = $this->container->em;

		$user = $em->getRepository('App_Model_User')
			       ->findOneBy(array('twitterId' => $json->id_str));

		if ($user == false) {
			$user = new App_Model_User;
			$user->setAccessToken($oauth_token_info['oauth_token']);
			$user->setAccessSecret($oauth_token_info['oauth_token_secret']);
			$user->setTwitterId($json->id_str);
			$user->setTwitterUsername($json->screen_name);
			$user->setLastLogin(new DateTime('now'));
			$user->setIsAdmin(false);
			$em->persist($user);
		} else {
			$user->setLastLogin(new DateTime('now'));
			$user->setTwitterUsername($json->screen_name);
		}

		$em->flush();

		$session->set('user', $user);
		
		return $this->redirect('/');
	}
}
