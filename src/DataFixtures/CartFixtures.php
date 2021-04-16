<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $products = $manager->getRepository(Product::class)->findAll();
        $order =  $manager->getRepository(Order::class)->findAll();


        for ($i = 0; $i < 120; $i++) {

            $cartItem = new Cart();

            $price = mt_rand(10, 2000);
            $cartItem->setProduct($products[mt_rand(0, count($products) -1)]);
            $cartItem->setRefOrder($order[mt_rand(0, count($order) -1)]);
            $cartItem->setQuantity(1);
            $cartItem->setPrice($price);
            $cartItem->setReducedPrice(null);
            $cartItem->setAmount($price);

            $manager->persist($cartItem);

        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            OrderFixtures::class,
            ProductFixtures::class,
        ];
    }
}
