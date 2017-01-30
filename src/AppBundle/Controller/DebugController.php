<?php
/**
 * Created by PhpStorm.
 * User: heiko
 * Date: 21.01.17
 * Time: 20:33
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin/debug")
 */
class DebugController extends Controller
{
    /**
     * @Route("/", name="debug")
     */
    public function debugAction()
    {
        $debug = null;
        require 'debug.php';#
        return new Response($debug);
    }
}