<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class TagController extends AbstractController
{
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }


    /**
     * @Route("/t/{type}", name="type")
     * @param TagRepository $tagRepository
     * @param Breadcrumbs $breadcrumbs
     * @param string $type
     * @return Response
     */
    public function typesTag(TagRepository $tagRepository,Breadcrumbs $breadcrumbs, string $type): Response
    {
        $tags = $tagRepository->findByType($type, null);

        /*--------------------------------------------------------------
        #  Breadcrumbs
        --------------------------------------------------------------*/
        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addItem("Voir les produits par ".$type);

        return $this->render('product/tag/type.html.twig', [
            'tags' => $tags,
        ]);
    }


    /**
     * @Route("/t/{type}/{slug}", name="tag")
     * @param TagRepository $tagRepository
     * @param string $slug
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param Breadcrumbs $breadcrumbs
     * @return Response
     */
    public function tag(TagRepository $tagRepository, string $slug, PaginatorInterface $paginator, Request $request, Breadcrumbs $breadcrumbs): Response
    {
        $tag = $tagRepository->findOneBy(['slug' => $slug]);

        $donnees = $tag->getProducts();
        $products = $paginator->paginate($donnees, $request->query->getInt('page', 1), 16);

        /*--------------------------------------------------------------
        #  Breadcrumbs
        --------------------------------------------------------------*/
        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addItem($tag->getType(), $this->router->generate('type', [
            'type' => $tag->getSlugType(),
        ]));
        $breadcrumbs->addItem($tag->getName());

        return $this->render('product/by-tags.html.twig', [
            'tag' => $tag,
            'products' => $products
        ]);
    }


    public function roomList(TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findByType("Pièce", null);

        return $this->render('product/tag/_tag-list.html.twig', [
            'tags' => $tags,
        ]);
    }

    public function randomRoom(TagRepository $tagRepository): Response
    {
        $rooms = $tagRepository->findByType("Pièce", 3);
        $rooms = (array)$rooms;

        return $this->render('pages/_rooms.html.twig', [
            'rooms' => $rooms,
        ]);
    }
}
