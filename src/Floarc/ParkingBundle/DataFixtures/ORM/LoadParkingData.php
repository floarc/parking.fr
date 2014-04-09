<?php
namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Floarc\ParkingBundle\Entity\Address;
use Floarc\ParkingBundle\Entity\GeoAddress;
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
		$this->types_duree = array();
		$this->types_place = array();
		$this->types_annonce = array();
	}
		
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		

		
		$addresses=$this->em->getRepository('FloarcParkingBundle:Address')->findAllOrderedById();
		
		$types_contrat=$this->em->getRepository('FloarcParkingBundle:TypeContrat')->findAllAsArray();
		$this->types_contrat = $types_contrat;

		$types_duree=$this->em->getRepository('FloarcParkingBundle:TypeDuree')->findAllAsArray();
		$this->types_duree = $types_duree;

		$types_place=$this->em->getRepository('FloarcParkingBundle:TypePlace')->findAllAsArray();
		$this->types_place = $types_place;

		$types_annonce=$this->em->getRepository('FloarcParkingBundle:TypeAnnonce')->findAllAsArray();
		$this->types_annonce = $types_annonce;		
		
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
		
		
		$nb_fixtures_parking = $this->container->getParameter('nb_fixtures_parking');
		for ($i = 1; $i <= $nb_fixtures_parking; $i++) {
			$parking = $this->createRandomParking($manager, $i);
			echo ($i)." parking created!\n";			
		}
		/*
		foreach ($addresses as $key => $address) {
			$parking = $this->createRandomParking($manager);
			//$parking = $this->createParking($address, $manager);
			
// 			echo "Location:\n";
// 			echo "<pre>";
// 			print_r($parking->getLocation());
// 			echo "</pre>";
// 			echo "\n";
			echo ($key+1)." parking created!\n";
		}
		*/
		

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
		$parking->setTitle($this->faker->sentence(5));
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
		$parking->setStatus(1);
		
		$type_contrat_index = $this->faker->randomNumber(0, (count($this->types_contrat)-1));
		$parking->setIdTypeContrat($this->types_contrat[$type_contrat_index]);
		
		$type_duree_index = $this->faker->randomNumber(0, (count($this->types_duree)-1));
		$parking->setIdTypeDureeMini($this->types_duree[$type_duree_index]);

		$type_place_index = $this->faker->randomNumber(0, (count($this->types_place)-1));
		$parking->setIdTypePlace($this->types_place[$type_place_index]);		
		
		$type_annonce_index = $this->faker->randomNumber(0, (count($this->types_annonce)-1));
		$parking->setIdTypeAnnonce($this->types_annonce[$type_annonce_index]);		
		
		$parking->setAccesHandicape($this->faker->boolean());
		
		$parking->setIdAddress($address);
		$parking->setLocation($address->getLat().",".$address->getLon());
		
		$parking->setCreatedAt($nowDateTime);
		$parking->setUpdatedAt($nowDateTime);
		
		$manager->persist($parking);
		$manager->flush();
		//unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);				
				
		
		return $parking;
		
	}	
	

	
	/**
	 * Create an entity parking
	 * @param ObjectManager $manager
	 * @return \Floarc\ParkingBundle\Entity\Parking|NULL
	 */
	protected function createRandomParking($manager, $i)
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
		$parking->setTitle($this->faker->sentence(5));
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
		$parking->setStatus(1);
	
		$type_contrat_index = $this->faker->randomNumber(0, (count($this->types_contrat)-1));
		$parking->setIdTypeContrat($this->types_contrat[$type_contrat_index]);
	
		$type_duree_index = $this->faker->randomNumber(0, (count($this->types_duree)-1));
		$parking->setIdTypeDureeMini($this->types_duree[$type_duree_index]);
	
		$type_place_index = $this->faker->randomNumber(0, (count($this->types_place)-1));
		$parking->setIdTypePlace($this->types_place[$type_place_index]);
	
		$type_annonce_index = $this->faker->randomNumber(0, (count($this->types_annonce)-1));
		$parking->setIdTypeAnnonce($this->types_annonce[$type_annonce_index]);
	
		$parking->setAccesHandicape($this->faker->boolean());
	
		

		
		$geoaddress = $this->em->getRepository('Floarc\ParkingBundle\Entity\GeoAddress')->find($i);
		$address = $this->createAddressFromGeoAddress($manager,$geoaddress);
		//$address = $this->createAddress($manager);
		
		if(!is_bool($address) && ($id_address = $address->getId()) && !empty($id_address) ){
			$parking->setIdAddress($address);
			$parking->setLocation($address->getLat().",".$address->getLon());
		}
		
		$parking->setCreatedAt($nowDateTime);
		$parking->setUpdatedAt($nowDateTime);
	
		$manager->persist($parking);
		$manager->flush();
	
		//unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);
		return $parking;
	
	}	
	
	/**
	 * Create an entity address
	 * @param ObjectManager $manager
	 * @return \Floarc\ParkingBundle\Entity\Address|NULL
	 */
	protected function createAddress($manager)
	{
	
	
		$return=false;
		
		$status="";
		$has_street_number = false;
 		
 		
 		
		while ($status!="OK" || $has_street_number != false) {
			
			
			sleep(6);
			if($status=="OVER_QUERY_LIMIT"){				
				continue;
			}
			//Marseille
			$lat_range = 57900;
			$lat = (43306500-rand(0, $lat_range))/1000000;
			$lon_range = 63500;
			$lon = (5364400-(rand(0, $lon_range)))/1000000;
			
			$url_json = "http://maps.googleapis.com/maps/api/geocode/json?language=fr-FR&latlng=$lat,$lon&sensor=true";
			echo $url_json."\n";
			$url_xml = "http://maps.googleapis.com/maps/api/geocode/xml?latlng=$lat,$lon&sensor=true";
			$output_json = $this->container->get('api_caller')->call(new HttpGetHtml($url_json, null ));
			$output_xml = $this->container->get('api_caller')->call(new HttpGetHtml($url_xml, null ));
		
			//$crawler = new Crawler($output_xml);
			$json_output = json_decode($output_json);
			
			//$status = $crawler->filter('status')->text();
			$status = $json_output->status;
			echo $status."\n";
				
 		
// 			echo "<pre>";
// 			print_r($json_output);
// 			echo "</pre>";			
			$results = $json_output->results;
			foreach ($results as $key => $result) {
				
// 				echo "<pre>";
// 				print_r($key);
// 				echo "</pre>";
								
				unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);
				$address_components = $result->address_components;
				//$address_components = $first_result->filter('address_component');
				$address = new Address();
	
// 				echo "<pre>";
// 				print_r($address_components);
// 				echo "</pre>";
				
				
				
				foreach ($address_components as $key => $address_component) {
						
						
					$types=$address_component->types;
					$type = $types[0];
					//echo $type."\n";
						
					switch ($type) {
						case "street_number":
							$has_street_number = true;
							$street_number = $address_component->short_name;
							$address->setNumeroRue($street_number);
							break;
						case "route":
							$route = $address_component->long_name;
							$address->setRoute($route);
							break;
	
						case "neighborhood":
							$neighborhood = $address_component->long_name;
							break;
	
						case "sublocality":
							$sublocality = $address_component->long_name;
							break;
	
						case "locality":
							$locality = $address_component->long_name;
							$address->setVille($locality);
							break;
	
						case "administrative_area_level_2":
							$ad_area_lv2 = $address_component->long_name;
							$departement = $ad_area_lv2;
							$address->setDepartement($departement);
							break;
	
						case "administrative_area_level_1":
							$ad_area_lv1 = $address_component->long_name;
							$region = $ad_area_lv1;
							$address->setRegion($region);
							break;
	
						case "country":
							$country = $address_component->short_name;
							$address->setPays($country);
							break;
	
						case "postal_code":
							$postal_code = $address_component->short_name;
							$address->setCodePostal($postal_code);
							break;
	
						default:
							break;
					}
						
						
				}
				
				
	
			}				
				
			if(!isset($street_number) ){
				
				continue;
			}


			$geometry = $result->geometry;
			$location = $geometry->location;

			$lat = $location->lat;
			$lon = $location->lng;

			if(isset($street_number) && isset($route)){
				$address->setAddress1($street_number." ".$route);
			}

			if(isset($lat)){
				$address->setLat($lat);
			}
			if(isset($lon)){
				$address->setLon($lon);
			}

			if(isset($lat) && !empty($lat) && isset($lon) && !empty($lon) ){
				$address->setLocation($lat.",".$lon);
			}

			$nowDateTime = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
			$address->setCreatedAt($nowDateTime);
			$address->setModifiedAt($nowDateTime);
			echo $street_number." ".$route."<br />";
			
			echo "status =".$status." ";
				
			if($has_street_number) {
				echo "has_street_number";
			}else{
				echo "has not street_number...";
			}
			echo "\n";			
			
			if(isset($street_number) && isset($route)){
				$manager->persist($address);
				$manager->flush();
				return $address;
			}
			unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);
	
		}
		
				
		
		return $return;
	
	}

	
	/**
	 * Create an entity address
	 * @param ObjectManager $manager
	 * @param GeoAddress $geoaddress
	 * @return \Floarc\ParkingBundle\Entity\Address|NULL
	 */
	protected function createAddressFromGeoAddress($manager, $geoaddress)
	{
	
		$return=false;
	

			
			
		$address = new Address();

		$street_number = $geoaddress->getNumeroRue();
		$address->setNumeroRue($street_number);
		$route = $geoaddress->getRoute();
		$address->setRoute($route);
		$ville = $geoaddress->getVille();
		$address->setVille($ville);
		$departement = $geoaddress->getDepartement();
		$address->setDepartement($departement);
		$region = $geoaddress->getRegion();
		$address->setRegion($region);
		$pays = $geoaddress->getPays();
		$address->setPays($pays);
		$postal_code = $geoaddress->getCodePostal();
		$address->setCodePostal($postal_code);
		$address->setAddress1($street_number." ".$route);
		$lat = $geoaddress->getLat();
		$address->setLat($lat);
		$lon = $geoaddress->getLon();
		$address->setLon($lon);
		$address->setLocation($lat.",".$lon);

		$nowDateTime = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
		$address->setCreatedAt($nowDateTime);
		$address->setModifiedAt($nowDateTime);

		if(isset($street_number) && isset($route)){
			$manager->persist($address);
			$manager->flush();
			return $address;
		}
		unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);
	
	
		return $address;
	
	}	
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 70; // the order in which fixtures will be loaded
	}	
}