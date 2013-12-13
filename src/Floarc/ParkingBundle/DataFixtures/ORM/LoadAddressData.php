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
		//$url = "http://maps.googleapis.com/maps/api/geocode/xml";
		
		
		$index = 100;
		for ($i = 0; $i < $index; $i++) {
			 
			$lat_range = 5600000;
			$lat = (43000000+rand(0, $lat_range))/1000000;
			
			$lng_range = 7000000;
			$lng = (-1000000+(rand(0, $lng_range)))/1000000;
			echo "iteration num°".$i."\n";
			$this->createAddress($lat, $lng, $manager);
			//48.6 ; -1
			//43 ; 6
			//dom 43,276863, 5,393973
		}
		
	}
	
	/**
	 * Create an entity address 
	 * @param string $lat
	 * @param string $lng
	 * @param ObjectManager $manager
	 * @return \Floarc\ParkingBundle\Entity\Address|NULL
	 */
	protected function createAddress($lat, $lng, $manager)
	{
		
		$url = "http://maps.googleapis.com/maps/api/geocode/xml?latlng=$lat,$lng&sensor=true";
		$output_xml = $this->container->get('api_caller')->call(new HttpGetHtml($url, null ));
		echo $url."\n";  
		
		
		$crawler = new Crawler($output_xml);
		
		$status = $crawler->filter('status')->text();
		print_r($status)."\n";
		if($status=="OK"){
			$first_result = $crawler->filter('result')->first();
			$address_components = $first_result->filter('address_component');
			foreach ($address_components as $key => $address_component) {
				$address = new Address();
				switch ($address_components->eq($key)->filter('type')->text()) {
					case "street_number":
						$street_number = $address_components->eq($key)->filter('short_name')->text();
						break;
					case "route":
						$route = $address_components->eq($key)->filter('long_name')->text();
						break;
						 
					case "neighborhood":
						$neighborhood = $address_components->eq($key)->filter('long_name')->text();
						break;
		
					case "sublocality":
						$sublocality = $address_components->eq($key)->filter('long_name')->text();
						break;
		
					case "locality":
						$locality = $address_components->eq($key)->filter('long_name')->text();
						$address->setVille($locality);
						break;
						 
					case "administrative_area_level_2":
						$ad_area_lv2 = $address_components->eq($key)->filter('long_name')->text();
						$departement = $ad_area_lv2;
						$address->setDepartement($departement);
						break;
						 
					case "administrative_area_level_1":
						$ad_area_lv1 = $address_components->eq($key)->filter('long_name')->text();
						break;
						 
					case "country":
						$country = $address_components->eq($key)->filter('short_name')->text();
						$address->setPays($country);
						break;
						 
					case "postal_code":
						$postal_code = $address_components->eq($key)->filter('short_name')->text();
						$address->setCodePostal($postal_code);
						break;
						 
					default:
						break;
				}
			}
			$lat = $first_result->filter('geometry > location > lat')->text();
			$lng = $first_result->filter('geometry > location > lng')->text();

			if(isset($street_number) && isset($route)){
				$address->setAddress1($street_number." ".$route);
			}

			if(isset($lat)){
				$address->setLat($lat);
			}
			if(isset($lng)){
				$address->setLng($lng);
			}
			$manager->persist($address);
			$manager->flush();
			unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lng);
			
		}else{
			$address=null;
		}
		return $address;
		
	}	
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 0; // the order in which fixtures will be loaded
	}	
}