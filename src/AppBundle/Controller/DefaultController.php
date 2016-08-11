<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Model\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/blog/show/{id}", requirements={"id" = "\d+"}, name="post_show")
     * @param $id
     * @return Response
     */
    public function showAction($id)
    {
        return new Response('TODO');
    }

    /**
     * @Route("/blog/page/{page}", requirements={"page" = "\d+"}, name="post_show_all")
     * @param $page
     * @return Response
     */
    public function listAction($page)
    {
        return new Response('TODO');
    }

    /**
     * @Route("/admin", name="admin")
     * @return Response
     */
    public function adminAction()
    {
        $debug = 'BUG' . PHP_EOL;
        echo phpinfo();
        return new Response();
    }
}
