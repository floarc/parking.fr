<?php
namespace Floarc\ParkingBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManager;
use Floarc\ParkingBundle\Entity\GeoAddress;
use Floarc\ParkingBundle\Entity\Address;
use Floarc\ParkingBundle\Entity\Parking;
use Floarc\ParkingBundle\Entity\TypeContrat;
use Floarc\ParkingBundle\Entity\TypeDuree;
use Floarc\ParkingBundle\Entity\TypePlace;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Call\HttpGetHtml;
use Lsw\ApiCallerBundle\Call\CurlCall;
use Symfony\Component\DomCrawler\Crawler;

class GetGeoAddressesCommand extends Command
{
	protected function configure()
	{
		$this
		->setName('floarc:addad')
		->setDescription('Recup d\'adresses geolocalise')
		->addArgument(
				'nb',
				InputArgument::OPTIONAL,
				'Combien d\'adresse souhaitez vous recup?'
		)
		/*
		->addOption(
				'yell',
				null,
				InputOption::VALUE_NONE,
				'Si défini, la réponse est affichée en majuscules'
		)
		*/
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->nb = $input->getArgument('nb');
		if ($this->nb) {
			$text = 'Salut, '.$this->nb;
			$this->output->writeln($text);
			$this->output->writeln($this->nb." geoaddresses will be created !");
			for ($i = 1; $i <= $this->nb; $i++) {
				
				
				$this->createGeoAdress();
				$this->output->writeln($i."/".$this->nb." geoaddresses created !");
			}
			$this->output->writeln($this->nb." geoaddresses created !");
			
		} else {
			$this->output->writeln("1 geoaddress will be created !");
			$this->createGeoAdress();
			$this->output->writeln("1 geoaddress created !");
		}

		/*
		if ($input->getOption('yell')) {
			$text = strtoupper($text);
		}
		*/
		
		
		
		
		
		



		
		
		
		
	}
	
	
	
	
	protected function createGeoAdress()
	{
		$geoaddress = null;
		$status = "";
		$has_street_number = false;
		
		while ($status!="OK" || $has_street_number != true) {
			sleep(10);
			if($status=="OVER_QUERY_LIMIT"){
				continue;
			}
			$this->output->writeln("");	
			$this->output->writeln("");	
			//Marseille
			$lat_range = 57900;
			$lat = (43306500-rand(0, $lat_range))/1000000;
			$lon_range = 63500;
			$lon = (5364400-(rand(0, $lon_range)))/1000000;
		
			$url_json = "http://maps.googleapis.com/maps/api/geocode/json?language=fr-FR&latlng=".$lat.",".$lon."&sensor=true";
			$this->output->writeln($url_json);
				
			$this->container = $this->getApplication()->getKernel()->getContainer();
				
			//$this->output_json = $this->container->get('api_caller')->call(new HttpGetHtml($url_json, null ));
			//$this->output_json = get_class($this->container->get('api_caller'));
			$output_json = $this->container->get('api_caller')->call(new HttpGetHtml($url_json, null ));
			$output_json_decoded = json_decode($output_json);
				
				
			$status = $output_json_decoded->status;
			//$this->output->writeln($status);
			
			//$this->output->writeln($output_json);
			$results = $output_json_decoded->results;
			$has_street_number = false;
			$has_route = false;
			foreach ($results as $key => $result) {
				unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);
				$address_components = $result->address_components;
				//$address_components = $first_result->filter('address_component');
				$geoaddress = new GeoAddress();
				foreach ($address_components as $key => $address_component) {
					$types=$address_component->types;
					$type = $types[0];
					switch ($type) {
						case "street_number":
							$street_number = $address_component->short_name;
							if(!empty($street_number)){
								$has_street_number = true;
								echo "street_number =".$street_number."\n";
								$geoaddress->setNumeroRue($street_number);								
							}
							break;
						case "route":
							$route = $address_component->long_name;
							if(!empty($route)){
								$has_route = true;
								echo "route =".$route."\n";
								$geoaddress->setRoute($route);							
							}
							break;
								
						case "neighborhood":
							$neighborhood = $address_component->long_name;
							echo "neighborhood =".$neighborhood."\n";
							break;
								
						case "sublocality":
							$sublocality = $address_component->long_name;
							echo "sublocality =".$sublocality."\n";
							break;
								
						case "locality":
							$locality = $address_component->long_name;
							if(!empty($locality)){
								$geoaddress->setVille($locality);
								echo "locality =".$locality."\n";							
							}							

							break;
								
						case "administrative_area_level_2":
							$ad_area_lv2 = $address_component->long_name;
							if(!empty($ad_area_lv2)){
								$geoaddress->setDepartement($ad_area_lv2);
								echo "ad_area_lv2 =".$ad_area_lv2."\n";
							}							
							
							break;
								
						case "administrative_area_level_1":
							$ad_area_lv1 = $address_component->long_name;
							$region = $ad_area_lv1;
							if(!empty($region)){
								$geoaddress->setRegion($region);
								echo "region =".$region."\n";							
							}							

							break;
								
						case "country":
							$country = $address_component->short_name;
							if(!empty($country)){
								$geoaddress->setPays($country);
								echo "country =".$country."\n";
							}

							break;
								
						case "postal_code":
							$postal_code = $address_component->short_name;
							if(!empty($postal_code)){
								$geoaddress->setCodePostal($postal_code);
								echo "postal_code =".$postal_code."\n";
							}							

							break;
						default:
							break;
					}
				}
				
				if($has_street_number==true  && $has_route==true) {
				
					$geometry = $result->geometry;
					$location = $geometry->location;
					
					$lat = $location->lat;
					$lon = $location->lng;
					
					if(isset($lat)){
						$geoaddress->setLat($lat);
					}
					if(isset($lon)){
						$geoaddress->setLon($lon);
					}
					
					if(isset($lat) && !empty($lat) && isset($lon) && !empty($lon) ){
						$geoaddress->setLocation($lat.",".$lon);
					}
					
					$nowDateTime = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
					$geoaddress->setCreatedAt($nowDateTime);
					$geoaddress->setModifiedAt($nowDateTime);
					
					
					if(isset($street_number) && isset($route)){
						$geoaddress->setAddress1($street_number." ".$route);
						echo $street_number." ".$route."\n";
							

					}
					$geoaddress->setJson($output_json);
					
					
					
					if($has_street_number==true  && $has_route==true) {
						break;
					}else{
						unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);
						$status = "";
						$has_street_number = false;
						$has_route = false;
						continue;
					}				
					break 2;
				}				
			}
		}
		
		$manager = $this->container->get("doctrine.orm.entity_manager");
		if(isset($street_number) /*&& isset($route)*/){
			$manager->persist($geoaddress);
			$manager->flush();
			echo "geoaddress saved...!\n";
			return $geoaddress;
		}
		unset($street_number,$route,$postal_code,$locality,$departement,$country,$lat,$lon);		
		

		return $geoaddress;
		

	}
	
}