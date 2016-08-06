<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /**
     * @Route("/post/create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new PostType(), new Post());
        $form->handleRequest();

        if ($form->isValid()) {
            $post = $form->getData();
            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('AppBundle:Post:edit.html.twig', array(
            'form' => $form,
        ));
    }

    /**
     * @Route("/post/edit/{id}")
     */
    public function editAction($id)
    {

        $post = $this->$this->getDoctrine()
        ->getRepository('AppBundle:Product')
        ->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form = $this->createForm(new PostType(), $post);
        $form->handleRequest();

        if ($form->isValid()) {
            $post = $form->getData();
            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('AppBundle:Post:edit.html.twig', array(
            'form' => $form,
        ));
    }

    /**
     * @Route("/post/delete/{id}")
     */
    public function deleteAction()
    {
        return $this->render('AppBundle:Post:delete.html.twig', array(
            // ...
        ));
    }

}
