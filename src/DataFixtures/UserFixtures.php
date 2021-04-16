<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface  $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        /*Ajoute un administrateur*/

        $userAdmin = new User();

        $password = $this->encoder->encodePassword($userAdmin, 'admin');

        $userAdmin->setUsername("admin");
        $userAdmin->setRegistration(new \DateTime());
        $userAdmin->setEmail("admin@gmail.com");
        $userAdmin->setPassword($password);
        $userAdmin->setRoles(array('ROLE_ADMIN'));
        $userAdmin->setIsSubscribed(0);
        $userAdmin->setIsContactMail(1);
        $userAdmin->setIsContactSms(0);

        $manager->persist($userAdmin);
        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);

        /*Ajoute des utilisateurs*/

        for ($i = 1; $i <= 20; $i++) {

            $user = new User();

            $user->setUsername("user".$i);
            $user->setEmail("user".$i."@gmail.com");

            $passwordUser = $this->encoder->encodePassword($user, "user".$i);
            $user->setPassword($passwordUser);
            $user->setIsSubscribed(mt_rand(0,1));
            $user->setIsContactMail(mt_rand(0,1));
            $user->setIsContactSms(mt_rand(0,1));
            $user->setRegistration(new \DateTime());


            $manager->persist($user);


        }

        $manager->flush();
    }
}
