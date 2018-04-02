<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class pruebasController extends Controller {
    
    public function indexAction(Request $request, $name, $page) {

        // formas de redireccionar
        // por nombre de la ruta
//        return $this->redirect($this->generateUrl("HolaMundo"));
        // usando request (se pone un parametro para saber que redirecciona bien)
//        return $this->redirect($request->getBaseUrl()."/helloWorld?hola=true");
        // sin usar request
//        return $this->redirect($this->container->get("router")->getContext()->getBaseUrl()."/helloWorld?hola=true");
        
//        var_dump($request->query->get("hola"));
//        var_dump($request->get("hola-post"));// se usa este más que el de arriba
//        die();
        
        $productos = array(
            array("producto"=>"consola 1","precio"=>100),
            array("producto"=>"consola 2","precio"=>200),
            array("producto"=>"consola 3","precio"=>300),
        );
        
        $fruta = array(
            "manzana"=>"golden","pera"=>"rica"
        );
        
        return $this->render('AppBundle:pruebas:index.html.twig', [
                    'texto' => $name . " - " . $page,
                    'productos' => $productos,
                    'fruta' => $fruta
        ]);
    }

}
