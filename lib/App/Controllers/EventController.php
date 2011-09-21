<?php
class App_Controllers_EventController extends Fwk_Controller
{
	public function indexAction(Fwk_Request $request)
	{
		$em = $this->container->em;
		$user = $this->container->session->get('user');

		$dql = 'SELECT e FROM App_Model_Event e WHERE e.published = 1 AND e.startDate > CURRENT_TIMESTAMP() ORDER BY e.startDate ASC';
		$query = $em->createQuery($dql);
		$events = $query->getResult();

		$nextEvent = $em->getRepository('App_Model_Event')->getNextEvent();

        $twig = $this->container->twig;
		return new Fwk_Response(200, $twig->render('event.list.html', array(
			'user' => $user,
			'events' => $events,
			'next_event' => $nextEvent
		)));
	}

	public function archiveAction(Fwk_Request $request)
	{
		$em = $this->container->em;
		$user = $this->container->session->get('user');

		$dql = 'SELECT e FROM App_Model_Event e WHERE e.published = 1 AND e.startDate < CURRENT_TIMESTAMP() ORDER BY e.startDate ASC';
		$query = $em->createQuery($dql);
		$events = $query->getResult();

		$nextEvent = $em->getRepository('App_Model_Event')->getNextEvent();

        $twig = $this->container->twig;
		return new Fwk_Response(200, $twig->render('event.list.html', array(
			'user' => $user,
			'events' => $events,
			'next_event' => $nextEvent
		)));
	}

	public function viewAction(Fwk_Request $request)
	{
		$em = $this->container->em;

		$id = $request->getParam('id');

		$session = $this->container->session;
		$user = $session->get('user');

		$attending = false;

		$event = $em->find('App_Model_Event', $id);

		if ($event == null) {
			return new Fwk_Response(404, 'Event not found');
		}

		if ($user !== null) {
			$user = $em->merge($user);
			$attending = $user->getEvents()->contains($event);
		}

		require APPLICATION_PATH . '/lib/php-markdown/markdown.php';
		$html = Markdown($event->getDescription());
		$event->description_html = $html;

		$twig = $this->container->twig;

		if (!$event) {
			return new Fwk_Response(404, $twig->render('404.html'));
		}

		$nextEvent = $em->getRepository('App_Model_Event')->getNextEvent();

		return new Fwk_Response(200, $twig->render('event.view.html', array(
			'event' => $event,
			'user' => $user,
			'next_event' => $nextEvent,
			'attendees' => $event->getAttendees(),
			'attending' => $attending
		)));
	}

	public function createAction(Fwk_Request $request)
	{
		$session = $this->container->session;
		$em = $this->container->em;
		$user = $session->get('user');

		if ($user == null || ($user !== null && $user->isAdmin() == false)) {
			return new Fwk_Response(401, 'Access denied');
		}

		$form = new App_Form_EventForm();

		if (null !== ($id = $request->getParam('id'))) {
			$dql = "SELECT e FROM App_Model_Event e WHERE e.id = ?1";
			$event = $em->createQuery($dql)
						->setParameter(1, $id)
						->getSingleResult(Doctrine\ORM\Query::HYDRATE_ARRAY);

			$event['start_date'] = $event['startDate']->format('d/m/Y H:i:s');

			if ($event !== false) {
				$form->setValues($event);
			}
		}

		if ($request->isPost()) {
			if ($form->isValid($_POST)) {
				if ($id == null) {
					$event = new App_Model_Event;
				} else {
					$event = $em->find('App_Model_Event',$id);
				}

				$event->setTitle($form->getValue('title'));
				$event->setDescription($form->getValue('description'));
				$event->setStartDate(DateTime::createFromFormat('d/m/Y H:i:s', $form->getValue('start_date')));
				$event->setPublished($form->getValue('published') == 1 ? 1 : 0);
				$event->setMapLink($form->getValue('mapLink'));
				$event->setMapText($form->getValue('mapText'));
				$em->persist($event);
				$em->flush();
			}
		}

		$nextEvent = $em->getRepository('App_Model_Event')->getNextEvent();

        $twig = $this->container->twig;
		return new Fwk_Response(200, $twig->render('admin/event.create.html', array(
			'form' => $form,
			'next_event' => $nextEvent,
			'user' => $user
		)));
	}

	public function deleteAction(Fwk_Request $request)
	{
		$user = $this->container->session->get('user');

		if (null == $user || ($user !== null && $user->isAdmin() == false)) {
			return new Fwk_Response(401, 'Access denied');
		}

		$id = $request->getParam('id');
		$em = $this->container->em;

		$event = $em->find('App_Model_Event', $id);

		if ($event == null) {
			return new Fwk_Response(404, 'Event not found');
		}

		$em->remove($event);
		$em->flush();

		header('Location: /');
	}

	public function attendAction(Fwk_Request $request)
	{
		$id = $request->getParam('id');
		$status = $request->getParam('status');
		$em = $this->container->em;
		$event = $em->find('App_Model_Event', $id);
		$user = $this->container->session->get('user');
		
		if ($user == null) {
			return new Fwk_Response(401, 'Access denied');
		}

		if ($event == null) {
			return new Fwk_Response(404, 'Event not found');
		}

		$user = $em->merge($user);

		if (!$status) {
			$event->getAttendees()->removeElement($user);
		} else {
			$event->getAttendees()->add($user);
		}

		$em->flush();

		header('Content-type: application/json');
		return new Fwk_Response(200, json_encode(array('success' => true)));
	}
}
