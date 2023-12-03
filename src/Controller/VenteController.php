<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VenteController extends AbstractController
{
    /**
     * Permet de pouvoir mettre des annonces
     *
     * @param VoitureRepository $repo
     * @return Response
     */
    #[Route('/vente', name: 'vente')]
    public function index(VoitureRepository $repo): Response
    {
        // On met "voitures" au pluriel pour pouvoir récupérer toutes les voitures
        $voitures = $repo->findAll();
        return $this->render('vente/index.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    /**
     * Permet de pouvoir ajouter une annonce 
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/vente/new', name: 'new')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $voiture = new Voiture();
        $myForm = $this->createForm(AnnonceType::class, $voiture);

        $myForm->handleRequest($request);

        if ($myForm->isSubmitted() && $myForm->isValid()) {
            $manager->persist($voiture);

            # j'envoie les persistances à la base de données.
            $manager->flush();

            # Permet d'avoir un message "Flash", d'où "addFlash" pour dire que l'annonce a bien été enregistrée !!!
            $this->addFlash('success', "L'annonce <strong>" . $voiture->getMarque() . "</strong> a bien été enregistrée");

            /**
             * Redirige si par exemple, l'annonce a bien été enregistrée, je retourne vars la page "show".
             */
            return $this->redirectToRoute('new');
        }

        return $this->render('vente/new.html.twig', [
            'myForm' => $myForm->createView()
        ]);
    }

    /**
     * Permet de faire en sorte que lorsque je clique sur le bouton "en savoir plus", je puisse avoir le descriptif de l'annonce.
     * Et donc l'annonce en entier.
     *
     * @param voiture $voiture
     * @return Response
     */
    #[Route('/vente/{slug}', name: 'vente_show')]
    public function show(voiture $voiture): Response
    {
        /**
         * $images = $voiture->getImages(); Ici je fais appel à la méthode getImages sur l'objet voiture et que j'attends d'avoir des images reliées à cet objet voiture...
         */
        $images = $voiture->getImages();
        return $this->render('vente/show.html.twig', [
            'voiture' => $voiture,
            // Ici, on met "image" au pluriel car on a plusieurs images (inverse que sur index.html.twig)
            'images' => $images
        ]);
    }
}
