<?php

namespace Floarc\ParkingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Floarc\ParkingBundle\Form\Type\RechercheType;
use Floarc\ParkingBundle\Form\Type\TaskType;
use Floarc\ParkingBundle\Form\Type\SearchType;
use Floarc\ParkingBundle\Entity\Parking;

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
}
