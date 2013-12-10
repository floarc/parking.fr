<?php

namespace Floarc\ParkingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FloarcParkingBundle:Default:index.html.twig', array('name' => $name));
    }
}
