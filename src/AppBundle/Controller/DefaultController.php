<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Repository\PostRepository;
use FOS\UserBundle\Model\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/base_frontend.html.twig');
    }

    /**
     * @Route("/ping", name="ping")
     */
    public function pingAction(Request $request)
    {
        return new JsonResponse('success', 200);
    }

    /**
     * @Route("/rss.xml", name="rss")
     */
    public function rssAction(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        $a = ['foo' => 'bar']; // todo
        $xml = new \SimpleXMLElement('<rss/>');
        array_walk_recursive($a, array($xml, 'addChild'));
        $response->setContent($xml->asXML());

        return $response;
    }

    /**
     * @Route("/cartoon/{slug}", name="post_show")
     * @param $slug
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($slug)
    {
        $post = $this->getPostRepo()->findOneBy(array(
            "slug"          => $slug,
            "isPublished" => true,
        ));

        if (null === $post) {
            throw new NotFoundHttpException('Blog entry could not be found');
        }

        return $this->render('pages/blog_item.html.twig', array('post' => $post));
    }

    /**
     * @Route("/api/blog/{id}", requirements={"id" = "\d+"}, name="api_post_show")
     * @param $id
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function apiShowAction($id)
    {
        $post = $this->getPostRepo()->find($id);

        if (null === $post) {
            return new JsonResponse('', 404);
        }

        return $this->render('components/blog_item.html.twig', array('post' => $post));
    }

    /**
     * @Route("/cartoons/{page}", requirements={"page" = "\d+"}, name="post_cartoons_all")
     * @param int $page
     * @return Response
     */
    public function cartoonList($page = 1)
    {
        $page  = $page >= 0 ? $page : 1;
        $posts = $this->getPostRepo()->getBlogPaginator($page, Post::CATEGORY_CARTOON);

        return $this->render('pages/list.html.twig', array('posts' => $posts));
    }

    /**
     * @Route("/blogs/{page}", requirements={"page" = "\d+"}, name="post_show_all")
     * @param $page
     * @return Response
     */
    public function listAction($page = 1)
    {
        $page  = $page >= 0 ? $page : 1;
        $posts = $this->getPostRepo()->getBlogPaginator($page);

        return $this->render('pages/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/api/blogs/{page}", requirements={"page" = "\d+"}, name="api_post_")
     * @param $page
     * @return Response
     */
    public function apiListAction($page)
    {
        $page = $page >= 0 ? $page : 0;
        $posts = $this->getPostRepo()->getBlogPaginator($page);

        $content =$this->render('components/list.html.twig', array('posts' => $posts));
        $content = $content->getContent();

        return new JsonResponse(['content' => $content]);
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

    /**
     * @return PostRepository
     */
    private function getPostRepo()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Post');
    }
}
