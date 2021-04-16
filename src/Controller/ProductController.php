<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use App\Form\AddToCartType;
use App\Form\ReviewType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class ProductController extends AbstractController
{

    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @Route("/products/all", name="shop")
     * @param ProductRepository $productRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $titlePage = "Tous les produits";
        $products = $productRepository->findAll();
        $products = $paginator->paginate($products, $request->query->getInt('page', 1), 18);


        return $this->render('product/index.html.twig', [
            'titlePage' => $titlePage,
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product")
     * @param Product $product
     * @param Request $request
     * @param string $slug
     * @param Breadcrumbs $breadcrumbs
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     * @param ReviewRepository $reviewRepository
     * @return Response
     */


    public function product(Product $product, Request $request, string $slug, Breadcrumbs $breadcrumbs, EntityManagerInterface $manager, UserRepository $userRepository, ReviewRepository $reviewRepository): Response
    {


        /*--------------------------------------------------------------
        #  Breadcrumbs
        --------------------------------------------------------------*/
        $breadcrumbs->prependRouteItem("Accueil", "home");
        $category = $product->getCategory()->getName();
        $slug = $product->getCategory()->getSlug();
        $breadcrumbs->addItem($category, $this->router->generate('category', [
            'slug' => $slug,
        ]));
        $breadcrumbs->addItem($product->getName());


        /*--------------------------------------------------------------
        #  Review
        --------------------------------------------------------------*/

        $user = $this->getUser();

        if($user) {
            $user = $userRepository->find($user);

            $review = $reviewRepository->findOneBy([
                'product' => $product,
                'user' => $user
            ]);
            if (!$review) {

                $review = new Review();
                $review->setProduct($product);
                $review->setUser($user);
                $review->setCreatedAt(new \DateTime());
            }
        } else {
            $review = new Review();
        }


        $reviewForm = $this->createForm(ReviewType::class, $review);

        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {

            if (!$this->isGranted('ROLE_USER')) {
                return $this->redirectToRoute('login');

            }

            $review->setUpdatedAt(new \DateTime());
            $manager->persist($review);
            $manager->flush();
            return $this->redirectToRoute('product', ['slug' => $product->getSlug()]);
        }

        return $this->render('product/single-product.html.twig', [
            'product' => $product,
            'reviewForm' => $reviewForm->createView(),
        ]);
    }


    /**
     * @Route("/products/sales", name="sales")
     * @param ProductRepository $productRepository
     * @param Breadcrumbs $breadcrumbs
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function productOnSale(ProductRepository $productRepository, Breadcrumbs $breadcrumbs, Request $request, PaginatorInterface $paginator): Response
    {
        $title = "Articles en promotion";

        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addItem("Promotions");

        $donnees = $productRepository->findAllByOnSale();

        $products = $paginator->paginate($donnees, $request->query->getInt('page', 1), 16);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'titlePage' => $title,
        ]);

    }

    /**
     * @Route("/products/new", name="new")
     * @param ProductRepository $productRepository
     * @param Breadcrumbs $breadcrumbs
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function newProduct(ProductRepository $productRepository, Breadcrumbs $breadcrumbs, Request $request, PaginatorInterface $paginator): Response
    {
        $title = "Nouveautés";
        $donnees = $productRepository->findBy([], ['updatedAt' => 'desc'], 24);
        $products = $paginator->paginate($donnees, $request->query->getInt('page', 1), 16);

        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addItem("Nouveautés");

        return $this->render('product/index.html.twig', [

            'products' => $products,
            'titlePage' => $title,
        ]);

    }

    public function latestProduct(): Response
    {
        $lastProducts = $this->getDoctrine()->getRepository(Product::class)->findBy([], ['updatedAt' => 'desc'], 9);

        return $this->render('product/_box-product.html.twig', [

            'products' => $lastProducts
        ]);
    }

    public function favProduct(ProductRepository $productRepository): Response
    {
        $favProduct = $productRepository->findByFav();

        return $this->render('product/_box-product.html.twig', [

            'products' => $favProduct
        ]);
    }

    public function totalGrade($reviews): int
    {
        $total = 0;

        foreach ($reviews as $review) {
            $total = $total + $review->getRate();
        }

        return ($total / count($reviews));
    }


}
