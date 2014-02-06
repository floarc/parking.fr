<?php
namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Floarc\ParkingBundle\Entity\TypePlace;

class LoadTypePlaceData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{

		$tabAllTypePlace = array();
		$tabAllTypePlace[]=array(
			"code" 	=> "parking_prive",
			"label" => "Parking Privé",
		);
		$tabAllTypePlace[]=array(
			"code" 	=> "parking_commercial",
			"label" => "Parking Commercial",
		);
		$tabAllTypePlace[]=array(
			"code" 	=> "garage",
			"label" => "Garage",
		);
		$tabAllTypePlace[]=array(
				"code" 	=> "box",
				"label" => "Box",
		);
		$tabAllTypePlace[]=array(
				"code" 	=> "allee",
				"label" => "Allée",
		);
		
		foreach ($tabAllTypePlace as $key => $tabTypePlace) {
			$typePlace = new TypePlace();
			$typePlace->setCode($tabTypePlace["code"]);
			$typePlace->setLabel($tabTypePlace["label"]);
				
			$manager->persist($typePlace);
			$manager->flush();
		}
		
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 40; // the order in which fixtures will be loaded
	}	
}