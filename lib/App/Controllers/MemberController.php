<?php

class App_Controllers_MemberController extends Fwk_Controller
{
	public function indexAction(Fwk_Request $request)
	{
		$em = $this->container->em;

		$dql = 'SELECT u FROM App_Model_User u';
		$users = $em->createQuery($dql)
			        ->getResult();

		$nextEvent = $em->getRepository('App_Model_Event')->getNextEvent();

		$twig = $this->container->twig;
		$session = $this->container->session;
		return new Fwk_Response(200, $twig->render('members.html', array(
			'members' => $users,
			'user' => $session->get('user'),
			'next_event' => $nextEvent
		)));
	}
}
