<?php

namespace Floarc\ParkingBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Floarc\ParkingBundle\Form\Type\RechercheType;
use Floarc\ParkingBundle\Form\Type\TaskType;
use Floarc\ParkingBundle\Form\Type\SearchType;
use Floarc\ParkingBundle\Entity\Parking;

use Floarc\ParkingBundle\Manager\ParkingManager;

class SearchController extends Controller
{
	/**
	 * @Route("/", name="search_index")
	 * @Template()
	 */	
    public function indexAction()
    {
    	$search_form = $this->createForm(new SearchType(), array());
 	
        return $this->render('FloarcParkingBundle:Default:index.html.twig', 
        	array(
        		'search_form' => $search_form->createView(),
        			
        	)
        );
    }
    
    
    /**
     * @Route("/ajax-search", name="search_ajax")
     * @Template()
     */
    public function ajaxAction(Request $request)
    {
    	
    	$finder = $this->get('fos_elastica.finder.parking.parking');
    	$em = $this->get('doctrine.orm.entity_manager');
    	$parkingManager = new ParkingManager($em, $finder);
//     	echo get_class($parkingManager);
//     	die();
    	$params = $this->getRequest()->query->all();
    	$return = json_encode($params);
    	
    	return new Response($return,200,array('Content-Type'=>'application/json'));
    	
    }    
}
