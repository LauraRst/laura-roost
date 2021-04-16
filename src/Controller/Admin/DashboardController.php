<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Post;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\Tag;
use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->count([]);
        $users = $this->getDoctrine()->getRepository(User::class);
        $nbUsers = $users->count([]);


        $date = date('Y-m-d h:i:s', strtotime("-30 days"));

        $recentUsers = count($users->findRecentUsers($date));

        $orders = $this->getDoctrine()->getRepository(Order::class)->count([
            'status' => 'Livré'
        ]);

        $posts = $this->getDoctrine()->getRepository(Post::class)->count([
                'isPublished' => 1
            ]

        );

        return $this->render('admin/dashboard.html.twig', [
            'products' => $products,
            'users' => $nbUsers,
            'orders' => $orders,
            'posts' => $posts,
            'recentUsers' => $recentUsers
        ]);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dekor SHOP')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('Gestion des clients', 'fa fa-user'),
            MenuItem::linkToCrud('Comptes utilisateurs', '',User::class),
            MenuItem::section('Gestion du catalogue','fa fa-shopping-basket'),
            MenuItem::linkToCrud('Liste des produits', '', Product::class),
            MenuItem::linkToCrud('Catégories de produits', '', Category::class),
            MenuItem::linkToCrud('Etiquettes', '', Tag::class),
            MenuItem::section('Service client', 'fa fa-headphones'),
            MenuItem::linkToCrud('Commandes', '', Order::class),
            MenuItem::linkToCrud('Avis', '', Review::class),

            MenuItem::section('Export et stats', 'fa fa-signal'),
            MenuItem::section('Blog', ' fa fa-file-text'),
            MenuItem::linkToCrud('Articles', '', Post::class),
            MenuItem::linkToLogout('Logout', 'fa fa-exit'),


        ];

    }
    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/admin.css');
    }

}