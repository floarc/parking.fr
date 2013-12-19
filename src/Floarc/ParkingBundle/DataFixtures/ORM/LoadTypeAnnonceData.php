<?php
namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Floarc\ParkingBundle\Entity\TypeAnnonce;

class LoadTypeAnnonceData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{

		$tabAllTypeAnnonce = array();
		$tabAllTypeAnnonce[]=array(
			"code" 	=> "offre",
			"label" => "Offre",
		);
		$tabAllTypeAnnonce[]=array(
			"code" 	=> "demande",
			"label" => "Demande",
		);
		
		foreach ($tabAllTypeAnnonce as $key => $tabTypeAnnonce) {
			$typeAnnonce = new TypeAnnonce();
			$typeAnnonce->setCode($tabTypeAnnonce["code"]);
			$typeAnnonce->setLabel($tabTypeAnnonce["label"]);
				
			$manager->persist($typeAnnonce);
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