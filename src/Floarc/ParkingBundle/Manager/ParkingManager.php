<?php
namespace Floarc\ParkingBundle\Manager;

use Doctrine\ORM\EntityManager;
use FOS\ElasticaBundle\Finder\TransformedFinder;
//use Elastica\Query\MatchAll;
//use Elastica\Query\MatchAll;
use Elastica\Type;
use Elastica\Query;
use Elastica\Filter;

/**
 * Project manager
 */
class ParkingManager
{
	protected $em;
	protected $finder;

	public function __construct(EntityManager $em, TransformedFinder $finder, Type $type)
	{
		$this->em     = $em;
		$this->finder = $finder;
		$this->type = $type;
	}

	public function getRepository()
	{
		return $this->em->getRepository('FloarcParkingBundle:User');
	}

	public function search($data)
	{
		if (isset($data["what"])) {
			$searchTerm = $data["what"];

			$elasticaQuery = new \Elastica_Query_QueryString($searchTerm);
		} else {
			//$elasticaQuery = new \Elastica_Query_MatchAll();
			//$elasticaQuery = new MatchAll();
			$elasticaQuery = new \Elastica\Query\MatchAll();
			/*
			$elasticaQuery = new \Elastica_Query_MatchAll();
			//$elasticaQuery = new Elastica\Query\MatchAll();
			//$elasticaQuery = new MatchAll();
			*/
						
			
		}

		/*
		if (isset($data["lat"]) && !empty($data["lat"]) && isset($data["lng"]) && !empty($data["lng"]) ) {
			

			try {
				
				

				//$geoFilter = new \Elastica_Filter_GeoDistance('id_address.lat', $data['lat'], $data['lng'], '10km');
				$geoFilter = new \Elastica\Filter\GeoDistance('id_address.lat', $data['lat'], $data['lng'], '10km');

				//$elasticaQuery = new \Elastica_Query_Filtered($elasticaQuery, $geoFilter);
				$elasticaQuery = new \Elastica\Query\Filtered($elasticaQuery, $geoFilter);
			} catch (Exception $e) {
			}
		}
		*/

		//$data = $this->finder->find($elasticaQuery);
		$data = $this->type->find($elasticaQuery);

		return $data;
	}
}