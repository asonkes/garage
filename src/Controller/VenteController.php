<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VenteController extends AbstractController
{
    /**
     * Permet de pouvoir créer des annonces, de pouvoir avoir accès à la page "Vente"
     *
     * @return Response
     */
    #[Route('/vente', name: 'vente')]
    public function index(VoitureRepository $repo): Response
    {
        // On met "voitures" au pluriel pour pouvoir récupérer toutes les voitures
        $voitures = $repo->findAll();
        return $this->render('vente/index.html.twig', [
            'voitures' => $voitures
        ]);
    }

    #[route('vente/{slug}', name: "vente_show")]
    public function show(voiture $voiture): Response
    {
        $images = $voiture->getImages();
        return $this->render('vente/show.html.twig', [
            /**
             * $images = $voiture->getImages(); Ici je fais appel à la méthode getImages sur l'objet voiture et que j'attends d'avoir des images reliées à cet objet voiture...
             */
            'voiture' => $voiture,
            // Ici, on met "image" au pluriel car on a plusieurs images (inverse que sur index.html.twig)
            'images' => $images
        ]);
    }
}
