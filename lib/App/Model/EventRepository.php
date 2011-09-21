<?php
use Doctrine\ORM\EntityRepository;

class App_Model_EventRepository extends EntityRepository
{
	public function getNextEvent()
	{
		$dql = 'SELECT e FROM App_Model_Event e WHERE e.published = 1 AND e.startDate > CURRENT_TIMESTAMP() ORDER BY e.startDate ASC';
		$query = $this->getEntityManager()->createQuery($dql)
										  ->setMaxResults(1);

		try {
			$event = $query->getSingleResult();
		} catch (Doctrine\ORM\NoResultException $e) {
			$event = null;
		}

		return $event;
	}
}
