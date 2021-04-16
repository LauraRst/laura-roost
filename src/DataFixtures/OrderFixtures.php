<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();

        for ($i = 0; $i < 10; $i++) {

            $order = new Order();

            $order->setUser($users[mt_rand(0, count($users) -1)]);
            $order->setStatus('Cart');
            $order->setCreatedAt(new \DateTime());
            $order->setTotalOrder(0);
            $manager->persist($order);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}
