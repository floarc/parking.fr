<?php

namespace Floarc\ParkingBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TypeDureeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TypeDureeRepository extends EntityRepository
{
	/**
	 * Retrieve TypeDuree ordered by id
	 * @return Ambigous <multitype:, \Doctrine\ORM\mixed, mixed, \Doctrine\DBAL\Driver\Statement, \Doctrine\Common\Cache\mixed>
	 */
	public function findAllAsArray()
	{
		return $this->getEntityManager()
		->createQuery(
				'SELECT td FROM FloarcParkingBundle:TypeDuree td ORDER BY td.id ASC'
		)
		->getResult();
	}
}