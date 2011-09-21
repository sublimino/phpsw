<?php
class App_Controllers_AccountController extends Fwk_Controller
{
	public function indexAction(Fwk_Request $request)
	{
		$user = $this->container->session->get('user');
		$twig = $this->container->twig;
		$em = $this->container->em;
		
		if ($user == null) {
			return new Fwk_Response(401, 'Access denied');
		}

		$nextEvent = $em->getRepository('App_Model_Event')->getNextEvent();
			
		return new Fwk_Response(200, $twig->render('user/account.html', array(
			'user' => $user,
			'next_event' => $nextEvent
		)));
	}

	public function removeAction(Fwk_Request $request)
	{
		$em = $this->container->em;
		$user = $this->container->session->get('user');
		
		if ($user == null) {
			return new Fwk_Response(401, 'Access denied');
		}

		$user = $em->merge($user);

		$em->remove($user);
		$em->flush();
			
		header('Location: /logout');
	}
}
