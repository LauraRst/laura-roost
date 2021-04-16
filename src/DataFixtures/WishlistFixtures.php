<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Wishlist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WishlistFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $products = $manager->getRepository(Product::class)->findAll();

        foreach ($users as $user) {

            $wishlist = new Wishlist();
            $wishlist->setCreatedAt(new \DateTime());
            $wishlist->setUpdatedAt(new \DateTime());

            for ($j = 0; $j < 10; $j++) {

                $wishlist->addProduct($products[mt_rand(0, count($products) - 1)]);
            }
            $user->setWishlist($wishlist);

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
