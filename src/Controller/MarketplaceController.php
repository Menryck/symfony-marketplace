<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarketplaceController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        return $this->render('marketplace/index.html.twig', [
            'controller_name' => 'MarketplaceController',
        ]);
    }

    /**
     * @Route("/annonces", name="annonces")
     */
    public function annonces(AnnonceRepository $annonceRepository): Response
    {
        // ICI ON VEUT AFFICHER LA LISTE DES ANNONCES
        // => SCENARIO CRUD : READ LISTE

        return $this->render('marketplace/annonces.html.twig', [
            // ON TRANSMET DE PHP A TWIG LA LISTE DES ANNONCES
            // DANS LA VARIABLE TWIG annonces
            'annonces' => $annonceRepository->findBy([], [ "datePublication" => "DESC" ]),
            // COMPTER TOUTES LES LIGNES DANS LA TABLE SQL annonce
            'annoncesTotal' => $annonceRepository->count([]),
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        return $this->render('marketplace/admin.html.twig', [
            'controller_name' => 'MarketplaceController',
        ]);
    }

    /**
     * @Route("/membre", name="membre")
     */
    public function new(Request $request): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('annonce_index');
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

}
