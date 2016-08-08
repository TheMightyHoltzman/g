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
        /**
         * @var $user User
         */
        $user = $this->getUser();
        var_dump($user->getRoles());
        echo phpinfo();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    public function showAction()
    {

    }

    public function listAction()
    {

    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request)
    {
        // replace this example code with whatever you need
        return new Response('This works');
    }
}
