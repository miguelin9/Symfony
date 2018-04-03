<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Curso;

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
    
    public function createAction() {
        $curso = new Curso();
        $curso->setTitulo("Curso de symfony3");
        $curso->setDescripcion("Curso completo de symfony");
        $curso->setPrecio(80);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($curso);
        $flush=$em->flush();
        
        if ($flush != null) {
            echo "El curso no se ha creado bien.";
        } else {
            echo "El curso se ha creado correctamente.";
        }
        
        die();
    }
    
    public function readAction() {
        $em = $this->getDoctrine()->getManager();
        $cursos_repo = $em->getRepository("AppBundle:Curso");
        $cursos = $cursos_repo->findAll();
            
        foreach ($cursos as $curso) {
            echo $curso->getId()."<br/>";
            echo $curso->getTitulo()."<br/>";
            echo $curso->getDescripcion()."<br/>";
            echo $curso->getPrecio()."<br/><hr/>";
        }
        
        echo "<h4>cursos de 80€</h4>";
        $cursos = $cursos_repo->findBy(array("precio"=>80));
            
        foreach ($cursos as $curso) {
            echo $curso->getId()."<br/>";
            echo $curso->getTitulo()."<br/>";
            echo $curso->getDescripcion()."<br/>";
            echo $curso->getPrecio()."<br/><hr/>";
        }
        
        echo "<h4>curso de 74€</h4>";
        $curso = $cursos_repo->findOneByPrecio(74);
        echo $curso->getTitulo();
        
        die();
    }
    
    public function updateAction($id, $titulo, $descripcion, $precio) {
        $em = $this->getDoctrine()->getManager(); 
        $cursos_repo = $em->getRepository("AppBundle:Curso");
        
        $curso = $cursos_repo->find($id);
        $curso->setTitulo($titulo);
        $curso->setDescripcion($descripcion);
        $curso->setPrecio($precio);
        
        $em->persist($curso);
        $flush=$em->flush();
        
        if ($flush != null) {
            echo "El curso no se ha actualizado.";
        } else {
            echo "El curso se ha actualizado correctamente.";
        }
        
        die();
    }
    
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $cursos_repo = $em->getRepository("AppBundle:Curso");
        
        $curso = $cursos_repo->find($id);
        $em->remove($curso);
        
        $flush = $em->flush();
        
        if ($flush != null) {
            echo "No se ha borrado bien.";
        } else {
            echo "Se ha borrado correctamente.";
        }
        
        die();
    }
    
    // Hacer una consulta nativa SQL
    public function nativeSqlAction() {
        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        
        $query = "SELECT * FROM cursos";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        
        $cursos = $stmt->fetchAll();
        
        foreach ($cursos as $curso) {
            echo $curso["titulo"]."<br/>";
        }
        
        die();
    }
    
        // Probando DQL (lenguaje propio de doctrine para consultas)
    public function dqlAction() {
        $em = $this->getDoctrine()->getManager();
        
        $query = $em->createQuery(
                "SELECT c FROM AppBundle:Curso c WHERE c.precio > :precio"
                )->setParameter("precio", "75");
        
        $cursos = $query->getResult();
        echo "<h4>Cursos mayores a 75€</h4>";
        foreach ($cursos as $curso) {
            echo $curso->getId()."<br/>";
            echo $curso->getTitulo()."<br/>";
            echo $curso->getDescripcion()."<br/>";
            echo $curso->getPrecio()."<br/><hr/>";
        }
        
        die();
    }

}
