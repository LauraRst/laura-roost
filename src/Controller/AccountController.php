<?php

namespace App\Controller;

use App\Form\AddressType;
use App\Form\PswdType;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(OrderRepository $orderRepository): Response
    {

        $user = $this->getUser();
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles(), true)) {
            return $this->redirect($this->generateUrl('admin'));
        }


        $lastOrder = $orderRepository->findOneBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'order' => $lastOrder
        ]);
    }

    /**
     * @Route("/account/profil", name="profile")
     * @param Breadcrumbs $breadcrumbs
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function profile(Breadcrumbs $breadcrumbs, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addRouteItem("Mon compte", "account");
        $breadcrumbs->addItem("Mes informations");

        $user = $this->getUser();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', "Vos identifiants ont été mis à jour.");
            return $this->redirectToRoute("profile");
        }

        $passwordForm = $this->createForm(PswdType::class, $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', "Votre mot de passe a été modifié.");
            return $this->redirectToRoute("profile");
        }

        $profileForm = $this->createForm(ProfileType::class, $user);
        $profileForm->handleRequest($request);
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', "Vos informations ont été mises à jour.");

            return $this->redirectToRoute("profile");
        }


        return $this->render('account/profile.html.twig', [

            'user' => $user,
            'userForm' => $userForm->createView(),
            'profileForm' => $profileForm->createView(),
            'passwordForm' => $passwordForm->createView()


        ]);

    }


    /**
     * @Route("/account/address", name="address")
     * @param Breadcrumbs $breadcrumbs
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function address(Breadcrumbs $breadcrumbs, Request $request, EntityManagerInterface $manager): Response
    {

        $breadcrumbs->prependRouteItem("Accueil", "home");
        $breadcrumbs->addRouteItem("Mon compte", "account");
        $breadcrumbs->addItem("Mon adresse");

        $user = $this->getUser();

        $addressForm = $this->createForm(AddressType::class, $user);
        $addressForm->handleRequest($request);

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', "Votre adresse a été mise à jour.");
            return $this->redirectToRoute("address");

        }

        return $this->render('account/address.html.twig', [
            'addressForm' => $addressForm->createView(),
            'user' => $user
        ]);
    }


}
