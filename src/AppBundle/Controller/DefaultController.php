<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Repository\PostRepository;
use FOS\UserBundle\Model\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
        return $this->redirect('/cartoon/latest');
    }

    /**
     * @Route("/ping", name="ping")
     */
    public function pingAction(Request $request)
    {
        return new JsonResponse('success', 200);
    }

    /**
     * @Route("/secure-cv")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCVAction(Request $request)
    {
        if ('GET' === $request->getMethod()) {
            $this->addFlash('error', 'You don\'t have the necessary credentials to access this content.');
            return $this->redirect('/professional');
        }
        if ('POST' === $request->getMethod()) {
            $language = $request->request->get('language');
            // check if they have the proper language
            if (!in_array($language, array('EN', 'DE'))) {
                $this->addFlash('error', 'Wrong language');
                return $this->redirect('/professional');
            }
            // check if they have the proper password
            if (!in_array($request->request->get('password'), array('123', '234'))) {
                $this->addFlash('error', 'Sorry, this is an invalid password :-(');
                return $this->redirect('/professional');
            }

            $path = $this->get('kernel')->getRootDir() . '/Resources/cv/Heiko_Mattern_resume.pdf';
            return new BinaryFileResponse($path, 200, array('Content-Type' => 'application/pdf'));
        }
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
     * @Route("/cartoon/{id}", name="cartoon_id")
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
     * @Route("/games/{name}", name="games")
     * @return Response
     */
    public function gamesAction($name)
    {
        $games = [
            'sudoku'        => [
                "/js/libs/p5/p5.min.js",
                "/js/p5/sudoku/cell.js",
                "/js/p5/sudoku/board.js",
                "/js/p5/sudoku/sudoku-solver.js",
            ],
            'mandelbrot'    => [
                "/js/libs/p5/p5.min.js",
                "/js/p5/mandelbrot.js",
            ],
            'snake'         => [
                "/js/libs/p5/p5.min.js",
                "/js/p5/snake/snake.js",
            ],
            'test'          => [
                "/js/libs/p5/p5.min.js",
                "/js/p5/test/test.js",
            ],
            'metaball'      => [
                "/js/libs/p5/p5.min.js",
                "/js/p5/metaball/metaball.js",
            ],
            'tetris'      => [
                "/js/libs/p5/p5.min.js",
                "/js/p5/tetris/tetris.js",
            ],
        ];

        if (! array_key_exists($name, $games)) {
            throw new NotFoundHttpException();
        }

        return $this->render('pages/games.html.twig', ['gamefiles' => $games[$name]]);
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
        $random     = $this->getPostRepo()->getRandom($excluded=[$post->getId()]);

        return $this->render('pages/cartoon.html.twig', array(
            'post'       => $post,
            'neighbours' => $neighbours,
            'random'     => $random));
    }
}
