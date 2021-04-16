<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $products = $manager->getRepository(Product::class)->findAll();

        for ($i = 0; $i < 40; $i++) {

            $review = new Review();
            $review->setContent("Lorem ipsum dolor sit amet");
            $review->setRate(mt_rand(0,5));

            $date = $this->randomDate('1 September 2020', 'now');

            $review->setCreatedAt(new \DateTime($date));
            $review->setUpdatedAt(new \DateTime($date));

            $review->setProduct($products[mt_rand(0, count($products) -1)]);
            $review->setUser($users[mt_rand(0, count($users) -1)]);

            $manager->persist($review);

        }

        $manager->flush();
    }

    function randomDate($start_date, $end_date)
    {
        $min = strtotime($start_date);
        $max = strtotime($end_date);

        $val = rand($min, $max);

        return date('Y-m-d H:i:s', $val);
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}
