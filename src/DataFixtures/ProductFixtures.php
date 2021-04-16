<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\ImageProduct;
use App\Entity\Product;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();

        for ($i = 0; $i < 60; $i++) {

            $product = new Product();

            $product->setName('Produit ' . $i);
            $product->setSlug('produit-'.$i);
            $product->setPrice(mt_rand(10, 2000));
            $product->setDescription("Description du produit " . $i);
            $product->setSale(mt_rand(0, 50));
            $product->setDimensions("H" . mt_rand(20, 100) . " x L" . mt_rand(40, 200) . " x PR" . mt_rand(40, 100));
            $product->setWeight(mt_rand(1, 45) . "KG");
            $product->setIsInStock(1);
            $product->setTva(21);
            $date = $this->randomDate('1 September 2020', 'now');

            $product->setCreatedAt(new \DateTime($date));
            $product->setUpdatedAt(new \DateTime($date));
            $product->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));

            $product->setCategory($categories[mt_rand(0, count($categories) - 1)]);
            $product->addTag($tags[mt_rand(0, count($tags) - 1)]);


                $image = new ImageProduct();
                $image->setFile("image-produit-".$i."-1.jpg");
                $image->setCreatedAt(new \DateTime($date));
                $image->setUpdatedAt(new \DateTime($date));

                $product->addImage($image);


            $manager->persist($product);

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
            CategoryFixtures::class,
            TagFixtures::class
        ];
    }
}
