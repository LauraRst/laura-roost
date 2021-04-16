<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Form\AddToCartType;
use App\Form\OrderType;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class CartController extends AbstractController
{
    /**
     * @Route("/account/cart/", name="cart")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepository
     * @param ProductRepository $productRepository
     * @param OrderRepository $orderRepository
     * @param CartRepository $cartRepository
     * @param Breadcrumbs $breadcrumbs
     * @return Response
     */
    public function cart(Request $request, EntityManagerInterface $manager, UserRepository $userRepository, ProductRepository $productRepository, OrderRepository $orderRepository, CartRepository $cartRepository, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addRouteItem("Continuer mes achats", "shop");
        $user = $userRepository->find($this->getUser()->getId());

        $order = $orderRepository->findCart(['user' => $user]);
        dump($order);
        if($order) {
            $nbItems = $order->getCarts()->count();
        }else{
            $nbItems = 0;
        }


         /*--------------------------------------------------------------
          # Form
          --------------------------------------------------------------*/

        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $action = $form->getClickedButton()->getName();


            if($action == "update"){

                $items =  $order->getCarts()->getValues();
                foreach ($items as $item) {

                    $amount =  $this->calculateAmountTTC($item->getPrice(), $item->getReducedPrice(), $item->getProduct()->getTva(), $item->getQuantity());
                    $item->setAmount($amount);

                    $manager->persist($order);
                    $manager->flush();

                }
            }
            if($action == "delete"){
                $manager->remove($order);
                $manager->flush();
            }
            if($action == "save"){
               $items = $order->getCarts()->getValues();
               $totalOrder = $this->calculateTotalOrder($items);
               
              $order->setTotalOrder($totalOrder);
              $order->setStatus('En cours de préparation');
                $this->addFlash('notice', 'Votre commande a été acceptée.');

                $manager->persist($order);
                $manager->flush();
                return $this->redirectToRoute('orders');
            }

        }


        return $this->render('account/cart.html.twig', [
            'form' => $form->createView(),
            'cart' => $order,
            'nbItems' => $nbItems,
            'user' => $user
        ]);
    }

    /*--------------------------------------------------------------
   #  Calcul Total Order
   --------------------------------------------------------------*/

    public function calculateTotalOrder($items): float
    {
        $total = 0;
        foreach ($items as $item) {

            $total = $total + $item->getAmount();

        }
        return $total;
    }
    /*--------------------------------------------------------------
    #  Add To Cart
    --------------------------------------------------------------*/

    /**
     * @Route("/account/cart/add/{id}", name="addToCart")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param int $id
     * @param UserRepository $userRepository
     * @param ProductRepository $productRepository
     * @param OrderRepository $orderRepository
     * @param CartRepository $cartRepository
     * @return Response
     */
    public function addToCart(EntityManagerInterface $manager, Request $request, int $id, UserRepository $userRepository, ProductRepository $productRepository, OrderRepository $orderRepository, CartRepository $cartRepository): Response
    {

        $product = $productRepository->find($id);
        $price = $product->getPrice();
        $sale = $product->getSale();
        $tva = $product->getTva();
        $reducedPrice = 0;

        if($sale > 0) {
            $reducedPrice = $this->calculateReducedPrice($price, $sale);
        }

        $pendingOrder = $orderRepository->findCart(['user' => $this->getUser()]);

        /*--------------------------------------------------------------
         #  Create if cart doesn't exit
         --------------------------------------------------------------*/
        if (!$pendingOrder) {
            $pendingOrder = $this->createCart();
        }

        /*--------------------------------------------------------------
         #  find item in cart
         --------------------------------------------------------------*/
        $cartItem = $cartRepository->findOneBy(
            [
                'product' => $product,
                'refOrder' => $pendingOrder
            ]
        );

        if($cartItem){
            $oldQuantity = $cartItem->getQuantity();
        }
        /*--------------------------------------------------------------
        #  Form
         --------------------------------------------------------------*/
        $cartForm = $this->createForm(AddToCartType::class, $cartItem, [
            'action' => $this->generateUrl('addToCart', ['id' => $id]),
        ]);
        $cartForm->handleRequest($request);

        if ($cartForm->isSubmitted() && $cartForm->isValid()) {

            if($cartItem){
                $quantity  = $oldQuantity + $cartItem->getQuantity();
                $cartItem->setQuantity($quantity);
                $item = $cartItem;
            }
            else {
                $newItem = new Cart();
                $quantity = $cartForm->getData()->getQuantity();
                $newItem->setQuantity($quantity);
                $newItem->setPrice($price);
                if($reducedPrice > 0) {
                    $newItem->setReducedPrice($reducedPrice);
                }
                $newItem->setProduct($product);
                $newItem->setRefOrder($pendingOrder);
                $item = $newItem;
            }

            $amount = $this->calculateAmountTTC($price, $reducedPrice, $tva, $quantity);
            $item->setAmount($amount);
            $manager->persist($item);

            $manager->flush();

            $this->addFlash('success', 'Ajouté à votre panier ');
            return $this->redirectToRoute('product', ['slug' => $product->getSlug()]);

        }


        return $this->render('product/_addToCart.html.twig', [
            'cartForm' => $cartForm->createView(),
            'cart' => $cartItem,
            'order' => $pendingOrder
        ]);
    }

    /**
     * @return Order
     */
    public function createCart(): Order
    {
        $order = new Order();
        $order
            ->setUser($this->getUser())
            ->setCreatedAt(new \DateTime())
            ->setStatus(Order::STATUS_CART)
            ->setTotalOrder(0);
        return $order;
    }

    public function calculateReducedPrice($price, $sale)
    :float
    {
        $reducedPrice = $price - (($price / 100) * $sale);
        return  round($reducedPrice, 1);
    }

    public function calculateAmountTTC($price, $reducedPrice, $tva, $quantity): float
    {

        if($reducedPrice) {
            $totalTTC = $reducedPrice + (($reducedPrice / 100) * $tva);
        }else {
            $totalTTC = $price + (($price / 100) * $tva);
        }

        return round($totalTTC, 1) * $quantity;
    }

    /**
     * @Route("/account/cart/del/{id}", name="deleteItem")
     * @param Cart $cartItem
     * @param EntityManagerInterface $manager
     * @return Response
     */
    function delItem(Cart $cartItem, EntityManagerInterface $manager): Response
    {
        $manager->remove($cartItem);
        $manager->flush();
        return $this->redirectToRoute('cart');
    }
}
