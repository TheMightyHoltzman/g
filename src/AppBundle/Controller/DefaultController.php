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
use Zend\Code\Scanner\DirectoryScanner;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $latest     = $this->getPostRepo()->getLatest();
        $neighbours = $this->getPostRepo()->getNeighbours($latest->getId());

        return $this->render('default/cartoon.html.twig', ['latest' => $latest, 'neighbours' => $neighbours]);
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
     * @Route("/cartoon/random", name="cartoon_random")
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function randomAction()
    {
        $random = $this->getPostRepo()->getRandom();
        $neighbours = $this->getNeighbours($random->getId());

    }

    /**
     * @Route("/cartoon/{id}", requirements={"id" = "\d+"}, name="cartoon_id")
     * @param $id
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function idAction($id)
    {
        return $this->getCartoon($id);
    }

    /**
     * @Route("/cartoon/{slug}", name="cartoon_slug")
     * @param $slug
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function slugAction($slug)
    {
        return $this->getCartoon($slug);
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
    public function cartoonList($page = 1) {
        $page  = $page >= 0 ? $page : 1;
        $posts = $this->getPostRepo()->getBlogPaginator($page, Post::CATEGORY_CARTOON);

        return $this->render('pages/list.html.twig', array('posts' => $posts));
    }

    /**
     * @Route("/blogs/{page}", requirements={"page" = "\d+"}, name="post_show_all")
     * @param $page
     * @return Response
     */
    public function listAction($page = 1) {
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
     * @Route("/games2/{name}", name="admin")
     * @return Response
     */
    public function game2Action($name)
    {
        $gamesDir  = $this->get('kernel')->getRootDir() . '/../web/js/games';
        $gamesDirs = scandir($gamesDir);

        foreach ($gamesDirs as $gameDir) {
            if ($name === $gameDir) {
                // TODO
            }
        }
    }

    /**
     * @return PostRepository
     */
    private function getPostRepo() {
        return $this->getDoctrine()->getRepository('AppBundle:Post');
    }

    /**
     * Finds and renders a cartoon
     *
     * @param $id
     * @return Response
     */
    private function getCartoon($id) {

        if ('latest' === $id) {
            $post = $this->getPostRepo()->getLatest();
        }
        elseif ('random' === $id) {
            $post = $this->getPostRepo()->getRandom();
        }
        elseif (!is_numeric($id)) {
            $params = ["slug" => $id, "isPublished" => true];
            $post = $this->getPostRepo()->findOneBy($params);
        }
        else {
            $params = ["id" => $id, "isPublished" => true];
            $post = $this->getPostRepo()->findOneBy($params);
        }

        if (null === $post) {
            throw new NotFoundHttpException('Blog entry could not be found');
        }

        $neighbours = $this->getPostRepo()->getNeighbours($post->getId());

        return $this->render('pages/cartoon.html.twig', array('post' => $post, 'neighbours' => $neighbours));
    }
}

