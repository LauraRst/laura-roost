<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class PostController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(['isPublished' => 1], ['updatedAt' => 'desc']);
        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="post")
     * @param PostRepository $postRepository
     * @param string $slug
     * @param Breadcrumbs $breadcrumbs
     * @return Response
     */

    public function product(PostRepository $postRepository,string $slug, Breadcrumbs $breadcrumbs): Response
    {

        $post = $postRepository->findOneBy(['slug' => $slug]);

        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addItem($post->getTitle());

        return $this->render('blog/single-post.html.twig', [

            'post' => $post,
        ]);
    }


    public function latestPost(): Response
    {
        $lastPosts = $this->getDoctrine()->getRepository(Post::class)->findBy(['isPublished' => 1], ['updatedAt' => 'desc'], 4);

        return $this->render('blog/_post.html.twig', [
            'posts' => $lastPosts
        ]);
    }
}
