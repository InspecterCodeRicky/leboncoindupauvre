<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/listes-annonces", name="listes_annonces")
     */
    public function getAnnonces(
        AnnonceRepository $repo,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $repo->findAll();

        $annonces = $paginator->paginate(
            $data,
            $request->query->getInt('page' , 1),
            6
        );

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/details-annonce/{id}", name="show_annonce")
     */
    public function showAnnonce($id, AnnonceRepository $repo): Response
    {

        $annonce = $repo->find($id);

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * @Route("/annonce/ajouter", name="ajouter_annonce")
     * @Route("/annonce/{id}/editer", name="editer_annonce")
     */
    public function fomrAnnonce(Annonce $annonce = null, Request $request, ObjectManager $manager)
    {
        if (!$annonce) {
            $annonce = new Annonce();
        }

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$annonce->getId()) {
                $annonce->setCreatedAt(new \DateTime());
            }

            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('show_annonce', ['id' => $annonce->getId()]);
        }

        return $this->render('annonce/create.html.twig', [
            'formAnnonce' => $form->createView(),
            'editMode' => $annonce->getId() !== null
        ]);
    }
}
