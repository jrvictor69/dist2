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
		return $query->getQuery()->getResult();
	}
}