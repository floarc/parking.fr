<?php
namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Floarc\ParkingBundle\Entity\TypeContrat;

class LoadTypeContratData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{

		$tabAllTypeContrat = array();
		$tabAllTypeContrat[]=array(
			"code" 	=> "vente",
			"label" => "Vente",
		);
		$tabAllTypeContrat[]=array(
			"code" 	=> "location",
			"label" => "Location",
		);
		$tabAllTypeContrat[]=array(
			"code" 	=> "colocation",
			"label" => "Colocation",
		);				
		
		foreach ($tabAllTypeContrat as $key => $tabTypeContrat) {
			$typeContrat = new TypeContrat();
			$typeContrat->setCode($tabTypeContrat["code"]);
			$typeContrat->setLabel($tabTypeContrat["label"]);
				
			$manager->persist($typeContrat);
			$manager->flush();
		}
		
	}
	
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 20; // the order in which fixtures will be loaded
	}	
}