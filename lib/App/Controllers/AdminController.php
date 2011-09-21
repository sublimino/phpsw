<?php
class App_Controllers_AdminController extends Fwk_Controller
{
	public function eventsAction(Fwk_Request $request)
	{
		$session = $this->container->session;
		$user = $session->get('user');

		if ($user == null || ($user !== null && $user->isAdmin() == false)) {
			return new Fwk_Response(401, 'Access denied');
		}

		$entityManager = $this->container->em;

		$dql = "SELECT e FROM App_Model_Event e ORDER BY e.startDate ASC";

		$query = $entityManager->createQuery($dql);
		$events = $query->getResult();

		$twig = $this->container->twig;
		return new Fwk_Response(200, $twig->render('admin/event.list.html', array(
			'events' => $events,
			'user' => $user
		)));
	}
}
