<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    function randomDate($start_date, $end_date)
    {
        $min = strtotime($start_date);
        $max = strtotime($end_date);

        $val = rand($min, $max);

        return date('Y-m-d H:i:s', $val);
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {

            $post = new Post();
            $post->setTitle('Lorem ipsum dolor sit amet '.$i);
            $post->setSlug('lorem-ipsum-dolor-sit-amet-'.$i);

            $post->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Faucibus purus in massa tempor nec feugiat nisl pretium fusce. Euismod nisi porta lorem mollis. Leo a diam sollicitudin tempor id eu nisl nunc mi. Felis donec et odio pellentesque diam volutpat commodo. Eu scelerisque felis imperdiet proin fermentum leo vel orci porta. In egestas erat imperdiet sed. Turpis cursus in hac habitasse platea dictumst. Odio euismod lacinia at quis risus sed vulputate. Lorem ipsum dolor sit amet. Eu turpis egestas pretium aenean pharetra magna ac placerat. Suspendisse sed nisi lacus sed.");


            $post->setIsPublished(1);

            $date = $this->randomDate('1 September 2020', 'now');

            $post->setCreatedAt(new \DateTime($date));
            $post->setUpdatedAt(new \DateTime($date));

            $post->setImage('blog-'.$i.'.jpg');
            $post->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
