<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class CategoryController extends AbstractController
{
    /**
     * @Route("/c/{slug}", name="category")
     * @param CategoryRepository $categoryRepository
     * @param string $slug
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param Breadcrumbs $breadcrumbs
     * @return Response
     */
    public function category(CategoryRepository $categoryRepository, string $slug,  Request $request, PaginatorInterface $paginator, Breadcrumbs $breadcrumbs): Response
    {

        $productCategory = $categoryRepository->findOneBy(['slug' => $slug]);

        $data = $productCategory->getProducts();

        $products = $paginator->paginate($data, $request->query->getInt('page', 1), 18);

        /*--------------------------------------------------------------
        #  Breadcrumbs
        --------------------------------------------------------------*/
        $breadcrumbs->prependRouteItem("Accueil", "home");

        $breadcrumbs->addItem($productCategory->getName());

        return $this->render('product/by-category.html.twig', [
            'category' => $productCategory,
            'products' => $products
        ]);
    }


    public function categoryList(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('product/category/_category-list.html.twig', [
            'productCategory' => $categories,
        ]);
    }
}
