<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\AnnonceType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VenteController extends AbstractController
{
    /**
     * Permet de pouvoir ajouter une annonce 
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/vente/new', name: 'new')]
    // Renvoi directement à la page connexion ==> sécurité, n'importe qui ne peut pas modifier une annonce dans le site
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $voiture = new Voiture();

        $myForm = $this->createForm(AnnonceType::class, $voiture);

        $myForm->handleRequest($request);

        if ($myForm->isSubmitted() && $myForm->isValid()) {

            // gestion des images 
            foreach ($voiture->getImages() as $image) {
                $image->setVoiture($voiture);
                $manager->persist($image);
            }

            // Intégration du user
            $voiture->setAuthor($this->getUser());

            $manager->persist($voiture);

            // J'envoie les persistances à la base de données.
            $manager->flush();

            // Permet d'avoir un message "Flash", d'où "addFlash" pour dire que l'annonce a bien été enregistrée !!!
            $this->addFlash('success', "L'annonce <strong>" . $voiture->getMarque() . "</strong> a bien été enregistrée");

            /**
             * Redirige si par exemple, l'annonce a bien été enregistrée, je retourne vars la page "show".
             */
            return $this->redirectToRoute('vente_show', [
                'slug' => $voiture->getSlug()
            ]);
        }

        return $this->render('vente/new.html.twig', [
            'myForm' => $myForm->createView()
        ]);
    }

    /**
     * Permet de pouvoir mettre des annonces
     * Voiturerepository $repo ==> permet de pouvoir gérer les requêtes de bases de données (spécifique ini, à "voiture".)
     *
     * @param VoitureRepository $repo
     * @return Response
     */
    #[Route('/vente', name: 'vente')]
    public function index(VoitureRepository $repo): Response
    {
        /**
         * Fonctionnement de la fonction FindAll(), permet de trouver toutes composants de l'entité "voiture"
         * On met "voitures" au pluriel car plusieurs "voitures" à trouver
         * 
         * On met "voitures" au pluriel pour pouvoir récupérer toutes les voitures
         */
        $voitures = $repo->findAll();
        return $this->render('vente/index.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    /**
     * Ici, on utilise "id" car les objets "voiture" sont identifiés de manière unqiue par un identifiant.
     * 
     * $request est un objet "request" contenant toute sle sinformations de la requête sql, y compris les données du formulaire.
     * 
     * $manager est un objet "EntityManagerInterface" qui gère la persistance des entités(objets) dans la base de données.
     */
    #[Route("/vente/{id}/edit", name: 'edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, EntityManagerInterface $manager, Voiture $voiture): Response
    {
        $form = $this->createForm(AnnonceType::class, $voiture);

        // Traite la requête HTTP actuelle avec les données du formulaire.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // gestion des images 
            foreach ($voiture->getImages() as $image) {
                $image->setVoiture($voiture);
                $manager->persist($image);
            }

            $manager->persist($voiture);

            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>" . $voiture->getMarque() . "</strong> a bien été modifiée!"
            );

            return $this->redirectToRoute('vente', [
                'slug' => $voiture->getSlug()
            ]);
        }

        return $this->render("vente/edit.html.twig", [
            "voiture" => $voiture,
            "myForm" => $form->createView()
        ]);
    }

    /**
     * Permet de faire en sorte que lorsque je clique sur le bouton "en savoir plus", je puisse avoir le descriptif de l'annonce.
     * 
     * Et donc l'annonce en entier.
     *
     * @param voiture $voiture
     * @return Response
     */
    #[Route('/vente/{slug}', name: 'vente_show')]
    public function show(voiture $voiture): Response
    {
        /**
         * Ici, on n'utilise pas $Voiture car ici, on a une partie dynamique {slug} qui est passé en tant que paramètre de fonction automatique pour convertir cette partie dynamique de l'URL en un objet de type "voiture"
         * 
         * Donc on va chercher dans la base de données, l'objet "voiture", correspondant à la valeur du "slug" = id (cela se fait par le système de routage) 
         */
        $images = $voiture->getImages();
        return $this->render('vente/show.html.twig', [
            'voiture' => $voiture,

            //Ici, "image" = pluriel plusieurs images.
            'images' => $images
        ]);
    }
}
