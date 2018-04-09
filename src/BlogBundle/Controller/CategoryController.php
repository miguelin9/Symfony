<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Session\Session;

class CategoryController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category_repo = $em->getRepository("BlogBundle:Category");
        $category = $category_repo->find($id);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $category = new Category();
                $category->setName($form->get('name')->getData());
                $category->setDescripcion($form->get('description')->getData());

                $em->persist($category);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "La categoria se ha editado correctamente";
                } else {
                    $status = "Error al editar la categoria";
                }
            } else {
                $status = "La categoria no se ha editado porque el formulario no es valido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute("blog_index_category");
        }

        return $this->render("BlogBundle:Category:edit.html.twig", array(
            "form" => $form->createView()
        ));
    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $category_repo = $em->getRepository("BlogBundle:Category");
        $categories = $category_repo->findAll();

        return $this->render("BlogBundle:Category:index.html.twig", array(
            "categories" => $categories
        ));

    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category_repo = $em->getRepository("BlogBundle:Category");
        $category = $category_repo->find($id);
        if (count($category->getEntries()) == 0) {
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('blog_index_category');
    }

    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $category = new Category();
                $category->setName($form->get('name')->getData());
                $category->setDescripcion($form->get('description')->getData());

                $em->persist($category);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "La categoria se ha creado correctamente";
                } else {
                    $status = "Error al añadir la categoria";
                }
            } else {
                $status = "La categoria no se ha creado porque el formulario no es valido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute("blog_index_category");
        }


        return $this->render("BlogBundle:Category:add.html.twig", array(
            "form" => $form->createView()
        ));
    }
}
