<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Tag;
use BlogBundle\Form\TagType;
use Symfony\Component\HttpFoundation\Session\Session;

class TagController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $tag_repo = $em->getRepository("BlogBundle:Tag");
        $tags = $tag_repo->findAll();

        return $this->render("BlogBundle:Tag:index.html.twig", array(
           "tags" => $tags
        ));

    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $tag_repo = $em->getRepository("BlogBundle:Tag");
        $tag = $tag_repo->find($id);
        if (count($tag->getEntryTag()) == 0) {
            $em->remove($tag);
            $em->flush();
        }
        return $this->redirectToRoute('blog_index_tag');
    }

    public function addAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $tag = new Tag();
                $tag->setName($form->get('name')->getData());
                $tag->setDescripcion($form->get('description')->getData());

                $em->persist($tag);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "La etiqueta se ha creado correctamente";
                } else {
                    $status = "Error al añadir la etiqueta";
                }
            } else {
                $status = "La etiqueta no se ha creado porque el formulario no es valido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute("blog_index_tag");
        }


        return $this->render("BlogBundle:Tag:add.html.twig", array(
            "form" => $form->createView()
        ));
    }
}
