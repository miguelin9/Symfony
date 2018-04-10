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
        $entry_repo = $em->getRepository("BlogBundle:Entry");

        $entry = $entry_repo->find($id);

        $tags_name = "";
        foreach ($entry->getEntryTag() as $entryTag) {
            $tags_name .= $entryTag->getTag()->getName() . ", ";
        }

        $form = $this->createForm(EntryType::class, $entry);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entry->setTitle($form->get('title')->getData());
                $entry->setContent($form->get('content')->getData());
                $entry->setStatus($form->get('status')->getData());

                // upload file
                $file = $form['image']->getData();
                $ext = $file->guessExtension();
                $file_name = time() . '.' . $ext;
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
                    $status = "La entrada se ha editado correctamente";
                } else {
                    $status = "Error al editar la entrada";
                }
            } else {
                $status = "La entrada no se ha editado porque el formulario no es valido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute("blog_homepage");
        }

        return $this->render("BlogBundle:Entry:edit.html.twig", array(
            "form" => $form->createView(),
            "entry" => $entry,
            "tags_name" => $tags_name
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entry_repo = $em->getRepository("BlogBundle:Entry");
        $entry_tag_repo = $em->getRepository('BlogBundle:EntryTag');

        $entry = $entry_repo->find($id);
        $entry_tags = $entry_tag_repo->findBy(array('entry' => $entry));

        foreach ($entry_tags as $entry_tag) {
            if (is_object($entry_tag)) {
                $em->remove($entry_tag);
                $em->flush();
            }
        }

        if (is_object($entry)) {
            $em->remove($entry);
            $em->flush();
        }

        return $this->redirectToRoute('blog_homepage');
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

                // upload file
                $file = $form['image']->getData();
                $ext = $file->guessExtension();
                $file_name = time() . '.' . $ext;
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
                    $status = "La entrada se ha creado correctamente";
                } else {
                    $status = "Error al añadir la entrada";
                }
            } else {
                $status = "La entrada no se ha creado porque el formulario no es valido";
            }

            $this->session->getFlashBag()->add('status', $status);
            return $this->redirectToRoute("blog_homepage");
        }


        return $this->render("BlogBundle:Entry:add.html.twig", array(
            "form" => $form->createView()
        ));
    }

    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $entry_repo = $em->getRepository('BlogBundle:Entry');

        $pageSize =6;
        $entries = $entry_repo->getPaginateEntries($pageSize, $page);

        $totalItems = count($entries);
        $pageCount = ceil($totalItems/$pageSize);

        return $this->render('BlogBundle:Entry:index.html.twig', array(
            'entries' => $entries,
            'totalItems' => $totalItems,
            'pagesCount' => $pageCount,
            'page' => $page
        ));
    }
}
