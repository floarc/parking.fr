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
		$url = "http://maps.googleapis.com/maps/api/geocode/xml?latlng=40.714224,-73.961452&sensor=true";
		$output_xml = $this->container->get('api_caller')->call(new HttpGetHtml($url, null ));
        //print_r($output);

        $crawler = new Crawler($output_xml);
        
        $status = $crawler->filter('status')->text();
        if($status=="OK"){
        	$first_result = $crawler->filter('result')->first();
        	$address_components = $first_result->filter('address_component');
        	foreach ($address_components as $key => $address_component) {
       		
        		
        		switch ($address_components->eq($key)->filter('type')->text()) {
        			case "street_number":
        				echo "street_number\n";
        				$street_number = $address_components->eq($key)->filter('short_name')->text();
        			break;
        			case "route":
        				echo "route\n";
        				$route = $address_components->eq($key)->filter('long_name')->text();
        			break;
        			
        			case "neighborhood":
        				echo "neighborhood\n";
        				$neighborhood = $address_components->eq($key)->filter('long_name')->text();
        			break;

        			case "sublocality":
        				echo "sublocality\n";
        				$sublocality = $address_components->eq($key)->filter('long_name')->text();
        			break;

        			case "locality":
        				echo "locality\n";
        				$locality = $address_components->eq($key)->filter('long_name')->text();
        			break;        			
        			
        			case "administrative_area_level_2":
        				echo "administrative_area_level_2\n";
        				$ad_area_lv2 = $address_components->eq($key)->filter('long_name')->text();
        				$departement = $ad_area_lv2;
        			break;
        			
        			case "administrative_area_level_1":
        				echo "administrative_area_level_1\n";
        				$ad_area_lv1 = $address_components->eq($key)->filter('long_name')->text();
        			break;
        					
        			case "country":
        				echo "country\n";
        				$country = $address_components->eq($key)->filter('short_name')->text();
        			break;
        			
        			case "postal_code":
        				echo "postal_code\n";
        				$postal_code = $address_components->eq($key)->filter('short_name')->text();
        			break;
        			
        			default:
        				;
        			break;
        		}

        		
        		
    		
        		
        		
        		
        	}
        	


        	$lat = $first_result->filter('geometry > location > lat')->text();
        	$lng = $first_result->filter('geometry > location > lng')->text();        	

        	$address = new Address();
        	if(isset($street_number) && isset($route)){
        		$address->setAddress1($street_number." ".$route);
        	}
        	if(isset($postal_code)){
        		$address->setCodePostal($postal_code);
        	}
        	if(isset($locality) ){
        		$address->setVille($locality);
        	}
        	if(isset($departement) ){
        		$address->setDepartement($departement);
        	}
        	if(isset($country) ){
        		$address->setPays($country);
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

        	 
        }
		/*
		$index = 100;
		for ($i = 0; $i < $index; $i++) {
			$address = new Address();
			$address->setCode($tabTypePlace["code"]);
			$address->setLabel($tabTypePlace["label"]);
			
			$address->persist($typePlace);
			$address->flush();
			//48.6 ; -1
			//43 ; 6
			//dom 43,276863, 5,393973
		}
		*/
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 0; // the order in which fixtures will be loaded
	}	
}