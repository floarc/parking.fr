<?php
namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Floarc\ParkingBundle\Entity\Parking;
use Floarc\ParkingBundle\Entity\TypeContrat;
use Floarc\ParkingBundle\Entity\TypeDuree;
use Floarc\ParkingBundle\Entity\TypePlace;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Call\HttpGetHtml;
use Lsw\ApiCallerBundle\Call\CurlCall;
use Symfony\Component\DomCrawler\Crawler;


class LoadParkingData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * @var EntityManager
	 */
	private $em;
	
	/**
	 * @var faker
	 */
	private $faker;	
	
	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
		$this->em = $container->get('doctrine.orm.entity_manager');
		$this->faker = $container->get('faker.generator');
		$this->types_contrat = array();
	}
		
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		

		
		$addresses=$this->em->getRepository('FloarcParkingBundle:Address')->findAllOrderedById();
		
		$types_contrat=$this->em->getRepository('FloarcParkingBundle:TypeContrat')->findAllAsArray();
		$this->types_contrat = $types_contrat;
		//$types_duree=$this->em->getRepository('FloarcParkingBundle:TypeDuree')->findAllOrderedById();
		//$types_place=$this->em->getRepository('FloarcParkingBundle:TypePlace')->findAllOrderedById();
		/*
		echo count($this->types_contrat)."\n";
		echo "<pre>";
		print_r($this->types_contrat);
		echo "</pre>";
		echo count($this->types_contrat)."\n";
		die("stop");
		*/
		
		foreach ($addresses as $key => $address) {
			$parking = $this->createParking($address, $manager);
			echo ($key+1)." parking created!\n";
		}
		

	}
	
	/**
	 * Create an entity parking
	 * @param Address $address
	 * @param ObjectManager $manager
	 * @return \Floarc\ParkingBundle\Entity\Parking|NULL
	 */
	protected function createParking($address, $manager)
	{
		
		$parking = new Parking();
		
		$nowDateTime = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
		
		
		
		
		
		/*
		$typePlace = $this->em->find('TypePlace', $this->faker->randomNumber(1, 5))
		$parking->setIdTypePlace($typePlace);
		
		$typeContrat = $this->em->find('TypeContrat', $this->faker->randomNumber(1, 5))
		$parking->setIdTypeContrat($typeContrat);		
		
		$typeContrat = $this->em->find('TypeContrat', $this->faker->randomNumber(1, 5))
		$parking->setIdTypeContrat($typeContrat);		
		*/
		$parking->setTitle($this->faker->sentence(200));
		$parking->setPrixMois($this->faker->randomNumber(80, 120));
		$parking->setPrixSemaine($this->faker->randomNumber(20, 50));
		$parking->setPrixJournee($this->faker->randomNumber(7, 15));
		$parking->setPrixVente($this->faker->randomNumber(10, 45)*1000);
		$parking->setCapacite($this->faker->randomNumber(1, 2));
		$parking->setDureeMini($this->faker->randomNumber(1, 10));
		$parking->setPrixNegociable($this->faker->boolean());
		$parking->setAcces24($this->faker->boolean());
		$parking->setFermeClef($this->faker->boolean());
		$parking->setCamera($this->faker->boolean());
		$parking->setGarde($this->faker->boolean());
		$parking->setUnderground($this->faker->boolean());
		$parking->setEclaireNuit($this->faker->boolean());
		$parking->setAbrite($this->faker->boolean());
		$parking->setAccesHandicape($this->faker->boolean());
		$parking->setIsApproved(1);
		
		$type_contrat_index = $this->faker->randomNumber(0, (count($this->types_contrat)-1));
		
		$parking->setIdTypeContrat($this->types_contrat[$type_contrat_index]);
		$parking->setAccesHandicape($this->faker->boolean());
		
		$parking->setCreatedAt($nowDateTime);
		$parking->setUpdatedAt($nowDateTime);
		
		$manager->persist($parking);
		$manager->flush();
		//unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lng);				
				
		
		return $parking;
		
	}	
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 50; // the order in which fixtures will be loaded
	}	
}