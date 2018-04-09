<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Entry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\EntryType;
use Symfony\Component\HttpFoundation\Session\Session;

class EntryController extends Controller
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
        $entry = new Entry();
        $form = $this->createForm(EntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $category_repo = $em->getRepository('BlogBundle:Category');
                $entry_repo = $em->getRepository('BlogBundle:Entry');

                $entry = new Entry();
                $entry->setTitle($form->get('title')->getData());
                $entry->setContent($form->get('content')->getData());
                $entry->setStatus($form->get('status')->getData());

                $file = $form['image']->getData();
                $ext = $file->guessExtension();
                $file_name=time() . '.' . $ext;
                $file->move('uploads', $file_name);
                $entry->setImage($file_name);

                $category = $category_repo->find($form->get('category')->getData());
                $entry->setCategory($category);

                $user = $this->getUser();
                $entry->setUser($user);

                $em->persist($entry);
                $flush = $em->flush();

                $entry_repo->saveEntryTags(
                    $form->get('tags')->getData(),
                    $form->get('title')->getData(),
                    $category,
                    $user
                );

                if ($flush == null) {
                    $status = "La categoria se ha creado correctamente";
                } else {
                    $status = "Error al añadir la categoria";
                }
            } else {
                $status = "La categoria no se ha creado porque el formulario no es valido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute("blog_homepage ");
        }


        return $this->render("BlogBundle:Entry:add.html.twig", array(
            "form" => $form->createView()
        ));
    }
}
