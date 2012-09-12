<?php
/**
 * Repository for Dist 2.
 *
 * @category Dist
 * @package Model
 * @subpackage Repository
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */
namespace Model\Repositories;

use Doctrine\ORM\EntityRepository;

/**
 * ClubPathfinderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClubPathfinderRepository extends EntityRepository {

	/**
	 *
	 * Alias of the table
	 * @var string
	 */
	private $_alias = 'pathfinder';

	/**
	 *
	 * Returns models according the filters
	 * @param array $filters
	 * @param int $limit
	 * @param int $offset
	 * @param int $sortColumn
	 * @param string $sortDirection
	 * @return Array Objects
	 */
	public function findByCriteria($filters = array(), $limit = NULL, $offset = NULL, $sortColumn = NULL, $sortDirection = NULL) {
		$query = $this->_em->createQueryBuilder();

		$query->select($this->_alias)
				->from($this->_entityName, $this->_alias)
				->setFirstResult($offset)
				->setMaxResults($limit);


		foreach ($filters as $filter) {
			$query->where("$this->_alias.".$filter['field'].' '.$filter['operator'].' :'.$filter['field']);
			$query->setParameter($filter['field'], $filter['filter']);
		}

		$sort = '';
		switch ($sortColumn) {
			case 1:
				$sort = 'name';
				break;

			case 2:
				$sort = 'textbible';
				break;

			case 3:
				$sort = 'textbible';
				break;

			case 4:
				$sort = 'created';
				break;

			case 5:
				$sort = 'changed';
				break;

			default: $sort = 'name';
		}

		$query->orderBy("$this->_alias.$sort", $sortDirection);

		return $query->getQuery()->getResult();
	}

	/**
	 *
	 * Finds count of models according the filters
	 * @param array $filters
	 * @return int
	 */
	public function getTotalCount($filters = array()) {
		$query = $this->_em->createQueryBuilder();

		$query->select("count($this->_alias.id)")
				->from($this->_entityName, $this->_alias);

		foreach ($filters as $filter) {
			$query->where("$this->_alias.".$filter['field'].' '.$filter['operator'].' :'.$filter['field']);
			$query->setParameter($filter['field'], $filter['filter']);
		}

		return (int)$query->getQuery()->getSingleScalarResult();
	}

	/**
	 * (non-PHPdoc)
	 * @see Doctrine\ORM.EntityRepository::findAll()
	 */
	public function findAll() {
		return $this->findBy(array('state' => 1));
	}

	/**
	 *
	 * Verifies if the name Club pathfinder already exist it.
	 * @param string $name
	 * @return boolean
	 */
	public function verifyExistName($name) {
		$object = $this->findOneBy(array('name' => $name, 'state' => TRUE));
		return $object != NULL? TRUE : FALSE;
	}

	/**
	 *
	 * Verifies if the id and name Club pathfinder already exist it.
	 * @param int $id
	 * @param string $name
	 */
	public function verifyExistIdAndName($id, $name) {
		$object = $this->findOneBy(array('id' => $id, 'name' => $name, 'state' => TRUE));
		return $object != NULL? TRUE : FALSE;
	}

	/**
	 * (non-PHPdoc)
	 * @see Doctrine\ORM.EntityRepository::find()
	 */
	public function find($id) {
		return $this->findOneBy(array('id' => $id, 'state' => TRUE));
	}

	/**
	 * Returns all club pathfinders
	 * @return array
	 */
	public function findAllArray() {
		$clubs = $this->findAll();

		$clubPathfinderArray = array();
		foreach ($clubs as $club) {
			$clubPathfinderArray[$club->getId()] = $club->getName();
		}

		return $clubPathfinderArray;
	}
}