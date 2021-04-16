<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private array $categories = ['Arts de la table','Décoration', 'Linge de maison et tapis', 'Luminaires', 'Meubles', 'Plantes d\'interieur'];
    
    public function load(ObjectManager $manager)
    {
        foreach ($this->categories as $category) {

            $productCategory = new Category();

            $string = str_replace([' ', "'"], '-', $category);

            $slug = strtolower( str_replace("é", 'e', $string));

            $productCategory->setName($category)
                ->setSlug($slug);

            $manager->persist($productCategory);
        }

        $manager->flush();
    }
}
