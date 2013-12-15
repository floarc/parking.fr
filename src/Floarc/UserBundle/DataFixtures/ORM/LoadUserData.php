<?php
namespace Floarc\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Floarc\UserBundle\Entity\User;

class LoadTypeUserData extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{

		$tabAllUserAdmin = array();
		$tabAllUserAdmin[]=array(
			"username" => "admin",
			"password" => "admin",
			"email" => "admin@domain.com",
		);
		
		foreach ($tabAllUserAdmin as $key => $tabUserAdmin) {
			$userAdmin = new User();
			$userAdmin->setUsername($tabUserAdmin["username"]);
			$userAdmin->setPassword($tabUserAdmin["password"]);
			$userAdmin->setEmail($tabUserAdmin["email"]);
			
			
			$manager->persist($userAdmin);
			$manager->flush();
		}
	
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 100; // the order in which fixtures will be loaded
	}	
}
