<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class OrderController extends AbstractController
{


    /**
     * @Route("/account/orders", name="orders")
     * @param Breadcrumbs $breadcrumbs
     * @param OrderRepository $orderRepository
     * @return Response
     */
    public function orders(Breadcrumbs $breadcrumbs, OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();

        $orders = $orderRepository->findOrders($user);

        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addRouteItem("Mon compte", "account");
        $breadcrumbs->addItem("Mes commandes");

        return $this->render('account/orders.html.twig', [
            'user' => $user,
            'orders' => $orders
        ]);
    }
    /**
     * @Route("/account/order/{id}", name="detail")
     * @param Order $order
     * @param CartRepository $cartRepository
     * @param Breadcrumbs $breadcrumbs
     * @return Response
     */
    public function orderDetail(Order $order, CartRepository $cartRepository, Breadcrumbs $breadcrumbs): Response
    {
        $user = $this->getUser();

        $items = $cartRepository->findBy(["refOrder" => $order->getId()]);

        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addRouteItem("Mon compte", "account");
        $breadcrumbs->addRouteItem("Mes commandes", "orders");
        $breadcrumbs->addItem("Ma commande");

        return $this->render('account/order-detail.html.twig', [
            'user' => $user,
            'order' => $order,
            'items' => $items
        ]);
    }


}
