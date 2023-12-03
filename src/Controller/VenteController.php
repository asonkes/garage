<?php

namespace App\Controller;

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
    public function index(): Response
    {
        return $this->render('vente/index.html.twig', []);
    }
}
