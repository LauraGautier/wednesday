<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifier si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            // Si l'utilisateur est déjà connecté, le rediriger vers la page des offres
            return $this->redirectToRoute('app_offers');
        }

        // Vérifier si une erreur de connexion existe
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupérer le dernier nom d'utilisateur tenté
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,  // Dernier nom d'utilisateur tenté
            'error' => $error,  // Afficher l'erreur si elle existe
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Ne pas oublier de rediriger ou d'ajouter un flash message pour le logout
        $this->addFlash('success', 'Vous avez été déconnecté avec succès.');
    }
}
