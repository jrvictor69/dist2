<?php

namespace Model\Repositories;

use Doctrine\ORM\EntityRepository;

/**
 * ClubPathfinderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClubPathfinderRepository extends EntityRepository {

	public function findByCriteria($filters = array(), $limit = NULL, $offset = NULL) {
		$query = $this->_em->createQueryBuilder();

		$query->select('pathfinders')
				->from($this->_entityName, 'pathfinders')
				->setFirstResult($offset)
				->setMaxResults($limit);

		return $query->getQuery()->getResult();
	}

	public function getTotalCount($filters = array()) {
		$query = $this->_em->createQueryBuilder();

		$query->select('count(pathfinder.id)')
				->from($this->_entityName, 'pathfinder');

		return (int)$query->getQuery()->getSingleScalarResult();
	}
}