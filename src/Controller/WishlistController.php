<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Wishlist;
use App\Form\WishlistType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class WishlistController extends AbstractController
{

    /**
     * @Route("/account/wishlist", name="wishlist")
     * @param Breadcrumbs $breadcrumbs
     * @return Response
     */
    public function wishlist(Breadcrumbs $breadcrumbs): Response
    {
        $user = $this->getUser();

        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addRouteItem("Mon compte", "account");
        $breadcrumbs->addItem("Ma liste d'envie");

        return $this->render('account/wishlist.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/p/{id}/wsh/", name="updateWishlist")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param int $id
     * @param UserRepository $userRepository
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function updateWishlist(EntityManagerInterface $manager, Request $request, int $id, UserRepository $userRepository, ProductRepository $productRepository): Response
    {

        $user = $userRepository->find($this->getUser());
        $wishlist = $user->getWishlist();
        $product = $productRepository->find($id);


        if ($wishlist->getProducts()->contains($product)) {
            $inWishlist = true;
        } else {
            $inWishlist = false;
        }

        $form = $this->createForm(WishlistType::class, $wishlist, [
            'action' => $this->generateUrl('updateWishlist', ['id' => $id]),
            'data_class' => null
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wishlist->setUpdatedAt(new \DateTime('now'));

            if ($inWishlist == false) {
                $wishlist->addProduct($product);
            } else {
                $wishlist->removeProduct($product);
            }
            $manager->persist($wishlist);
            $manager->flush();


            return $this->redirectToRoute('wishlist');

        }

        return $this->render('product/_updWishlist.twig', [
            'product' => $product,
            'inWishlist' => $inWishlist,
            'form' => $form->createView()
        ]);


    }


}
