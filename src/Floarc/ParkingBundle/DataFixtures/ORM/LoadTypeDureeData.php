<?php
namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Floarc\ParkingBundle\Entity\TypeDuree;

class LoadTypeDureeData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{

		$tabAllTypeDuree = array();
		$tabAllTypeDuree[]=array(
			"code" 	=> "jour",
			"label" => "Jour",
		);
		$tabAllTypeDuree[]=array(
			"code" 	=> "semaine",
			"label" => "Semaine",
		);
		$tabAllTypeDuree[]=array(
			"code" 	=> "mois",
			"label" => "Mois",
		);				
		
		foreach ($tabAllTypeDuree as $key => $tabTypeDuree) {
			$typeDuree = new TypeDuree();
			$typeDuree->setCode($tabTypeDuree["code"]);
			$typeDuree->setLabel($tabTypeDuree["label"]);
				
			$manager->persist($typeDuree);
			$manager->flush();
		}
		
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 3; // the order in which fixtures will be loaded
	}	
}