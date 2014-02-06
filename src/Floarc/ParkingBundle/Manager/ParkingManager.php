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
		
		
		$elasticaQuery = new \Elastica\Query();
		
		
	
		echo "<pre>";
		print_r($data);
		echo "</pre>";		
		
		$filters = $this->defaultFilters();

		// Add filter to the search object.
		
		
		if(count($data)==0){
				
				
		}
		

		if(array_key_exists("type_annonce", $data) && !empty($data["type_annonce"])){
			// Filter on type_annonce parking 
			$elasticaFilterTypeAnnonce   = new \Elastica\Filter\Term();
			$elasticaFilterTypeAnnonce->setTerm('id_type_annonce.code', $data["type_annonce"]);			
			
			$filters->addFilter($elasticaFilterTypeAnnonce);
		}
		
		if(array_key_exists("type_contrat", $data) && !empty($data["type_contrat"])){
			// Filter on type_annonce parking 
			$elasticaFilterTypeContrat   = new \Elastica\Filter\Term();
			$elasticaFilterTypeContrat->setTerm('id_type_contrat.code', $data["type_contrat"]);			
			
			$filters->addFilter($elasticaFilterTypeContrat);
		}
		
		
		$filters    = new \Elastica\Filter\BoolAnd();
		
		
		
		if (isset($data["lat"]) && !empty($data["lat"]) && isset($data["lng"]) && !empty($data["lng"]) ) {
			

			try {
				
				

				//$geoFilter = new \Elastica_Filter_GeoDistance('id_address.lat', $data['lat'], $data['lng'], '10km');
				//$geoFilter = new \Elastica\Filter\GeoDistance('id_address.lat', array('lat' => $data['lat'], 'lon' => $data['lat']), '10km');
				
				
				//$filters->addFilter($geoFilter);

				//$elasticaQuery = new \Elastica_Query_Filtered($elasticaQuery, $geoFilter);
				//$elasticaQuery = new \Elastica\Query\Filtered($elasticaQuery, $geoFilter);
			} catch (Exception $e) {
			}
		}
		
		//echo $elasticaQuery->__toString();
// 		echo "<pre>";
// 		print_r($elasticaQuery);
// 		echo "</pre>";
		
		$elasticaQuery->setFilter($filters);
		
		echo "<pre>";
		print_r(json_encode($elasticaQuery->toArray()));
		echo "</pre>";		
		
		//$data = $this->finder->find($elasticaQuery);
		$data = $this->type->search($elasticaQuery);

		return $data;
	}
	
	
	public function defaultFilters()
	{
		
		// Filter on parking annonce status
		$elasticaFilterStatus   = new \Elastica\Filter\Term();
		$elasticaFilterStatus->setTerm('status', '1');		
	
		// Filter on parking annonce is_approved
		$elasticaFilterIsApproved   = new \Elastica\Filter\Term();
		$elasticaFilterIsApproved->setTerm('is_approved', '1');
		
		$elasticaFilterAnd    = new \Elastica\Filter\BoolAnd();
		$elasticaFilterAnd->addFilter($elasticaFilterStatus);
		$elasticaFilterAnd->addFilter($elasticaFilterIsApproved);
	
		return $elasticaFilterAnd;
	}	
}