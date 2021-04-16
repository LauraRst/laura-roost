<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{

    public function header(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();
        $cart = 0;
        $user = $this->getUser();

        if($user) {
        $order = $this->getDoctrine()->getRepository(Order::class)->findCart($this->getUser());

            if($order) {
                $cart = $order->getCarts()->count();
            }

        }
        return $this->render('pages/_header.html.twig', [
            'categories' => $categories,
            'tags' => $tags,
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', [
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('pages/about.html.twig');
    }


    /**
     * @Route("/faq", name="faq")
     */
    public function faq(): Response
    {
        return $this->render('pages/faq.html.twig');
    }

    /**
     * @Route("/politique-de-confidentialite", name="privacy")
     */
    public function terms(): Response
    {
        return $this->render('pages/privacy.html.twig');
    }

    /**
     * @Route("/conditions-generales-de-vente", name="cgv")
     */
    public function cgv(): Response
    {
        return $this->render('pages/cgv.html.twig');
    }
}
