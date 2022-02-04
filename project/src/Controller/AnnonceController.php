<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Commentaire;
use App\Form\AnnonceType;
use App\Form\CommentaireFormType;
use App\Repository\AnnonceRepository;
use App\Repository\CommentaireRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Cache\ItemInterface;

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
     * @Route("/details-annonce/{id}-{slug}", name="show_annonce")
     */
    public function showAnnonce($id, $slug, AnnonceRepository $repo, CommentaireRepository $commenRepo, Request $request, CacheInterface $cache): Response
    {

        $annonce = $repo->find($id);

        $annonce = $cache->get('show_annonce_'.$id.''.$slug, function(ItemInterface $item) use($repo, $slug){
            $item->expiresAfter(20);
            return $repo->findOneBy(['slug' => $slug]);
        });

        if(!$annonce){
            throw new NotFoundHttpException('Pas d\'annonce trouvée');
        }

        $comment = new Commentaire();

        $commentForm = $this->createForm(CommentaireFormType::class, $comment);

        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setCreatedAt(new DateTime());
            $comment->setAnnonce($annonce);

            $parentid = $commentForm->get("parentid")->getData();

            if($parentid != null){
                $parent = $commenRepo->getRepository(Commentaire::class)->find($parentid);
            }

            $comment->setParent($parent ?? null);

            $commenRepo->persist($comment);
            $commenRepo->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('show_annonce', ['id' => $annonce->getId() , 'slug' => $annonce->getSlug()]);
        }


        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'commentForm' => $commentForm->createView()
        ]);
    }

}
