<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tags = array(
            'Bureau' => 'Pièce',
            'Cuisine' => 'Pièce',
            'Chambre' => 'Pièce',
            'Cuisine' => 'Pièce',
            'Salle de bain' => 'Pièce',
            'Jardin' => 'Pièce',
            'Salon' => 'Pièce',
            'Salle à manger' => 'Pièce',
            'Métal' => 'Style',
            'Verre' => 'Style',
            'Tissu' => 'Style',
            'Bois' => 'Style',
            'Marbre' => 'Style',
            'Velours' => 'Style',
            'Céramique' => 'Style',
            'Lin' => 'Style',
            'Cuivre' => 'Style',
            'Béton' => 'Style',
            'Rotin' => 'Style'
        );

        foreach ($tags as $name => $type ) {

            $tag = new Tag();
            $tag->setName($name);
            $tag->setType($type);

            $string = str_replace("é", 'e', $name);
            $string = str_replace("à", 'a', $string);
            $slug = strtolower( str_replace(" ", '-', $string));
            $tag->setSlug($slug);

            $slugType =  str_replace("è", 'e', $type);
            $tag->setSlugType(strtolower($slugType));

            $manager->persist($tag);
        }

        $manager->flush();
    }
}
