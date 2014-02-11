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

use Elastica\Query\Match;
use Elastica\Query\Text;
use Elastica\Query\Bool;
use Elastica\Query;


class SearchController extends Controller
{
	/**
	 * @Route("/", name="search_index")
	 * @Template()
	 */	
    public function indexAction(Request $request)
    {
    	//$dataSearchType=$this->getDataSearchForm($request);
    	$dataSearchType=array();
    	if ($request->getMethod() == 'POST') { // Si on a soumis le formulaire
    		$dataSearchType = $this->getRequest()->request->all();
    	}else{
    		$dataSearchType = $this->getRequest()->query->all();
    	}    	
    	$search_form = $this->createForm(new SearchType(), $dataSearchType);

    	    	
 	
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
    	$type = $this->get('fos_elastica.index.parking.parking');
    	$em = $this->get('doctrine.orm.entity_manager');
    	$parkingManager = new ParkingManager($em, $finder, $type);
//     	echo get_class($parkingManager);
//     	die();
    	$params = $this->getRequest()->query->all();
    	$return = json_encode($params);
    	
    	//$dataSearchType=$this->getDataSearchForm($request);
    	$dataSearchType=array();
    	if ($request->getMethod() == 'POST') { // Si on a soumis le formulaire
    		$dataSearchType = $this->getRequest()->request->all();
    	}else{
    		$dataSearchType = $this->getRequest()->query->all();
    	}

    	
    	//$search_form = $this->createForm(new SearchType(), $dataSearchType);
    	$search_form = $this->createForm(new SearchType(), array());
    	$search_form->handleRequest($request);
    	
    	if ($search_form->isValid()) {
    		// fait quelque chose comme sauvegarder la tâche dans la bdd
    		//return $this->redirect($this->generateUrl('task_success'));
    		//$res = $finder->search($dataSearchType);
    		return new Response($return,200,array('Content-Type'=>'application/json'));
    		//return new Response($res,200,array('Content-Type'=>'application/json'));
    	}else{
    		return new Response("form not valid",200,array('Content-Type'=>'application/json'));
    	}
    	
    }
    
    /**
     * @Route("/search-search", name="search_search")
     * @Template()
     */
    public function searchAction(Request $request)
    {

    	$finder = $this->get('fos_elastica.finder.parking.parking');
    	$type = $this->get('fos_elastica.index.parking.parking');
    	$em = $this->get('doctrine.orm.entity_manager');
    	//$parkingManager = new ParkingManager($em, $finder, $type);
    	$parkingManager = $this->get('parking.manager');
    	    	
    	//     	echo get_class($parkingManager);
    	//     	die();
    	$params = $this->getRequest()->request->all();
    	$return = json_encode($params);
    	 
    	$dataSearchType=array();
    	if ($request->getMethod() == 'POST') { // Si on a soumis le formulaire
    		$dataSearchType = $this->getRequest()->request->all();
    	}else{
    		$dataSearchType = $this->getRequest()->query->all();
    	}
    
    	 
    	$search_form = $this->createForm(new SearchType(), $dataSearchType);
    	//$search_form = $this->createForm(new SearchType(), array());
    	$search_form->handleRequest($request);
    	 
    	if ($search_form->isValid()) {
    		//echo "valid";
    		
    		// fait quelque chose comme sauvegarder la tâche dans la bdd
    		//return $this->redirect($this->generateUrl('task_success'));
    		//return new Response($return,200,array('Content-Type'=>'application/json'));
    		

    		$res = $parkingManager->search($dataSearchType);
    		
    		//$index = $this->get('fos_elastica.index.afsy');
    		//$index = $this->get('fos_elastica.index.parking.parking');
    		//echo get_class($index);
    		//die("stop");
    		
    		
    		
    		//$res = $index->search("Saepe");
    		
    		
    		//$q = new Query(array());
    		
    		
    		//$q = new Bool();
    		//$qText = new Text();
    		//$qText->setParam("title","Saepe");
    		//$q->addShould($qText);    		
    		
    		//$q = new Query($qText);
    		
    		/*
    		echo "<pre>";
    		print_r($qText->toArray());
    		echo "</pre>";
    		echo "-----------------------------------<br />";
    		echo "<pre>";
    		print_r(json_encode($qText->toArray()));
    		echo "</pre>";
    		*/
    		
    		/*
    		echo "<pre>";
    		print_r($q->toArray());
    		echo "</pre>";
    		echo "-----------------------------------<br />";
    		echo "<pre>";
    		print_r(json_encode($q->toArray()));
    		echo "</pre>";
    		//echo "-----------------------------------<br />";
    		die("stop");
    		*/
    		
    		///$res = $index->search($qText);
    		
    		    		

    		$paginator  = $this->get('knp_paginator');
    		$pagination = $paginator->paginate(
    				$res->getResults(),
    				$this->getRequest()->request->get('page', 1)/*page number*/,
    				$this->getRequest()->request->get('nb', 50)/*limit per page*/
    		);    	
    		/*	
    		echo "<pre>";
    		print_r($pagination);
    		echo "</pre><br />";
    		*/
    		
    		/*
    		echo $res->count()."<br />";
    		echo "<pre>";
    		print_r($res->getResults());
    		echo "</pre><br />";
    		echo "-----------------------------------<br />";
    		echo "<pre>";
    		print_r($pagination);
    		echo "</pre><br />";
    		echo "-----------------------------------<br />";    		
    		die("stop");
    		*/
    		    		
    		//return new Response($return,200,array('Content-Type'=>'application/json'));
    		//return new Response($res,200,array('Content-Type'=>'application/json'));

    		
    		return $this->render('FloarcParkingBundle:Default:index.html.twig',
    				array(
    						'pagination' => $pagination,
    						'search_form' => $search_form->createView(),
    						 
    				)
    		);    		
    		
    		
    	}else{
    		echo "not valid";
    		return new Response("form not valid",200,array('Content-Type'=>'application/json'));
    	}
    	 
    }    

    /**
     * @Route("/", name="search_index")
     * @Template()
     */
    /*
    public function getDataSearchForm(Request $request)
    {
    	$dataSearchType=array();
    	if ($request->getMethod() == 'POST') { // Si on a soumis le formulaire
    		$dataSearchType = $this->getRequest()->request->all();
    	}else{
    		$dataSearchType = $this->getRequest()->query->all();
    	}
 
    	return $dataSearchType;
    } 
    */   
}
