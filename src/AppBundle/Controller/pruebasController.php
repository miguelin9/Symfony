<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class pruebasController extends Controller {
    
    public function indexAction(Request $request) {

        // replace this example code with whatever you need
        return $this->render('AppBundle:pruebas:index.html.twig', [
                    'texto' => "Texto enviado desde la acción del controlador."
        ]);
    }

}
