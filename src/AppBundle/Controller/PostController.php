<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/blog")
 */
class PostController extends Controller
{
    /**
     * @Route("/create", name="post_create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new PostType(), new Post());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $post = $form->getData();
            $post->setCreatedAt(new \DateTime())->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->container->get('router')->generate('post_edit', array('id' => $post->getId())));
        }

        return $this->render('AppBundle:Post:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="post_edit")
     */
    public function editAction(Request $request, $id)
    {
        $post = $this->getDoctrine()
        ->getRepository('AppBundle:Post')
        ->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form = $this->createForm(new PostType(), $post);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $post = $form->getData();
            $post->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('AppBundle:Post:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}",  requirements={"id" = "\d+"}, name="post_delete")
     */
    public function deleteAction($id)
    {
        return $this->render('AppBundle:Post:delete.html.twig', array(
            // ...
        ));
    }

}
