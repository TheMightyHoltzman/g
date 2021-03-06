<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/home", name="post_home")
     */
    public function homeAction(Request $request)
    {
        return $this->render('AppBundle:Post:home.html.twig');
    }

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

            if (true === $post->getIsPublished()) {
                $post->setPublishedAt(new \DateTime());
            }
            elseif ($post->getPublishedAt()) {
                $post->setPublishedAt(null);
            }

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
            /**
             * @var $post Post
             */
            $post = $form->getData();
            $post->setUpdatedAt(new \DateTime());

            if (true === $post->getIsPublished()) {
                $post->setPublishedAt(new \DateTime());
            }
            elseif ($post->getPublishedAt()) {
                $post->setPublishedAt(null);
            }

            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('AppBundle:Post:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/list/{page}", name="post_list", requirements={"page" = "\d+"})
     */
    public function listAction($page)
    {
        $posts       = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();
        return $this->render('AppBundle:Post:list.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * @Route("/delete/{id}",  requirements={"id" = "\d+"}, name="post_delete")
     */
    public function deleteAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $this->getDoctrine()->getManager()->remove($post);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->get('router')->generate('post_list', array(
            'page' => 1,
        )));
    }
}
