<?php

namespace App\Controller;

use App\Entity\Candidacy;
use App\Entity\User;
use App\Form\CandidacyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidacyController extends AbstractController
{
    /**
     * Afficher la liste des candidatures.
     */
    #[Route('/candidacies', name: 'app_candidacy_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les candidatures
        $candidacies = $entityManager->getRepository(Candidacy::class)->findAll();

        return $this->render('app/candidacy.html.twig', [
            'candidacies' => $candidacies,
        ]);
    }

/**
 * Ajouter une nouvelle candidature.
 */
#[Route('/candidacy/new', name: 'app_candidacy_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $candidacy = new Candidacy();

    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login'); // Rediriger si l'utilisateur n'est pas connecté
    }

    // Créer le formulaire
    $form = $this->createForm(CandidacyType::class, $candidacy);
    $form->handleRequest($request);

    // Vérifier si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist et flush uniquement si le formulaire est valide
        $entityManager->persist($candidacy);
        $entityManager->flush();

        // Rediriger vers la page de liste des candidatures
        return $this->redirectToRoute('app_candidacy_index');
    }

    // Afficher le formulaire
    return $this->render('app/addCandidacy.html.twig', [
        'form' => $form->createView(),
    ]);
}

    /**
     * Modifier une candidature existante.
     */
    #[Route('/candidacy/edit/{id}', name: 'app_candidacy_edit')]
    public function edit(string $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $candidacy = $entityManager->getRepository(Candidacy::class)->find($id);

        $form = $this->createForm(CandidacyType::class, $candidacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour la candidature
            $entityManager->persist($candidacy);
            $entityManager->flush();

            // Rediriger vers la page de liste des candidatures
            return $this->redirectToRoute('app_candidacy_index');
        }

        return $this->render('app/editCandidacy.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Supprimer une candidature.
     */
    #[Route('/candidacy/delete/{id}', name: 'app_candidacy_delete')]
    public function delete(Candidacy $candidacy, EntityManagerInterface $entityManager): Response
    {
        // Supprimer la candidature
        $entityManager->remove($candidacy);
        $entityManager->flush();

        // Rediriger vers la page de liste des candidatures
        return $this->redirectToRoute('app_candidacy_index');
    }
}
