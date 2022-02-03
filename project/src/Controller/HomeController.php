<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnonceRepository $repoAnnonce, CategoryRepository $repoCategory): Response
    {
        $annonces = $repoAnnonce->findBy([], ['id' => 'DESC'], 3);

        $categories = $repoCategory->findAll();

        return $this->render('home/index.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories
        ]);
    }
}
