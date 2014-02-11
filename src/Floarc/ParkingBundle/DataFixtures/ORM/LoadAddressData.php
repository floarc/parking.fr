<?php
namespace Floarc\ParkingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Floarc\ParkingBundle\Entity\Address;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Call\HttpGetHtml;
use Lsw\ApiCallerBundle\Call\CurlCall;
use Symfony\Component\DomCrawler\Crawler;


class LoadAddressData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
		
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		
		//$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&sensor=true";
		//$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=43.276814,5.393883&sensor=true";
		//$url = "http://maps.googleapis.com/maps/api/geocode/xml";
		
		$nb_fixtures_adresses = $this->container->getParameter('nb_fixtures_adresses');
		$index = $nb_fixtures_adresses;
		$i=0;
		$t=0;
		//for ($i = 0; $i < $index; $i++) {
		while ($i < $index) {		
			$t++;
			

			//France
			//48.6 ; -1
			//43 ; 6			
			
			$lat_range = 5600000;
			$lat = (43000000+rand(0, $lat_range))/1000000;
			
			$lon_range = 7000000;
			$lon = (-1000000+(rand(0, $lon_range)))/1000000;
			
			
			
			//Marseille
			//43.306500 ; 5.364400		range		-57900			
			//43.266300 ; 5.427900		range		-63500
			
			//43.28,5.4
			
			$lat_range = 57900;
			$lat = (43306500-rand(0, $lat_range))/1000000;
				
			$lon_range = 63500;
			$lon = (5364400-(rand(0, $lon_range)))/1000000;			
			
			
			$resCreateAddress = $this->createAddress($lat, $lon, $manager);
			
			///if($this->createAddress(43.276814, 5.393883, $manager)!=false){
			//echo "test num�".$t."\n";
			if(is_object($resCreateAddress)){
				//echo "  - succes num�".$i."\n";
				$i++;
				echo $i." address created!\n"; 
			}
			
			//48.6 ; -1
			//43 ; 6
			//dom 43,276863, 5,393973
		}
		
	}
	
	/**
	 * Create an entity address 
	 * @param string $lat
	 * @param string $lon
	 * @param ObjectManager $manager
	 * @return \Floarc\ParkingBundle\Entity\Address|NULL
	 */
	protected function createAddress($lat, $lon, $manager)
	{
		

		$return=false; 
		$url_json = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=true";
		$url_xml = "http://maps.googleapis.com/maps/api/geocode/xml?latlng=$lat,$lon&sensor=true";
		$output_json = $this->container->get('api_caller')->call(new HttpGetHtml($url_json, null ));
		$output_xml = $this->container->get('api_caller')->call(new HttpGetHtml($url_xml, null ));
		
		

		//$crawler = new Crawler($output_xml);
		$json_output = json_decode($output_json);
		
		//$status = $crawler->filter('status')->text();
		$status = $json_output->status;
		//print_r($status)."\n";
		if($status=="OK"){
			$results = $json_output->results;
			foreach ($results as $key => $result) {
				unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);
				$address_components = $result->address_components;
				//$address_components = $first_result->filter('address_component');
				$address = new Address();

				
				foreach ($address_components as $key => $address_component) {
					
					
					$types=$address_component->types;
					$type = $types[0];
					
					switch ($type) {
						case "street_number":
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
				
				if(!isset($street_number) ){
					continue;
				}
				
//  				echo "<pre>"."\n";
// 				print_r($result);
// 				echo "</pre>"."\n";

// 				echo "route ".$route."\n";
// 				echo "street_number ".$street_number."\n";				
// 				echo "locality ".$locality."\n";
				
// 				echo "departement ".$departement."\n";
// 				echo "region ".$region."\n";
// 				echo "postal_code ".$postal_code."\n";
				
// 				echo "<pre>"."\n";
// 				print_r($address);
// 				echo "</pre>"."\n";				
								
//				echo $url_json;
				
				$geometry = $result->geometry;
				$location = $geometry->location;
				
				//$lat = $first_result->filter('geometry > location > lat')->text();
				//$lon = $first_result->filter('geometry > location > lng')->text();
				
				//echo "adresse=>  ".$route." ".$street_number." ".$street_number." ".$departement."\n";
				
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
				
				//$manager->persist($address);
				//$manager->flush();
				
				
				
				
				if(isset($street_number) && isset($route)){
					$manager->persist($address);
					$manager->flush();
					return $address;
				}
				unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);

			}

			
		}
		return $return;
		
	}	
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 10; // the order in which fixtures will be loaded
	}	
}