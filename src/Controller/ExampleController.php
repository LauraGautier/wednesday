<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\User;
use App\Form\OfferType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ExampleController extends AbstractController
{
    #[Route('/offers', name:'app_offers')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

    // Si l'utilisateur n'est pas connecté, vous pouvez rediriger ou afficher un message d'erreur
        if (!$user) {
    // Par exemple, rediriger vers la page de login
        return $this->redirectToRoute('app_login');
        }
        $offers = $entityManager->getRepository(Offer::class)->findAll();
        return $this->render('offers/index.html.twig', [
            'user' => $user,
            'offers' => $offers
        ]);
    }
    #[Route('/add-offer', name: 'app_add_offer')]
    public function addOffer(EntityManagerInterface $entityManager, Request $request): Response
    {
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $entityManager->getRepository(User::class)->findOneBy([
                'email' => 'test@test.fr'
            ]);
            $offer->setRecruiter($user);
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_offers');
        }

        return $this->render('app/addOffer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit-offer/{id}', name:'app_edit_offer')]
    public function editOffer(EntityManagerInterface $entityManager, Request $request, string $id)
    {
        $offer = $entityManager->getRepository(Offer::class)->find($id);

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $entityManager->getRepository(User::class)->findOneBy([
                'email' => 'test@test.fr'
            ]);
            $offer->setRecruiter($user);
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_offers');
        }

        return $this->render('app/editOffer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/delete-offer/{id}', name:'app_delete_offer')]
    public function deleteOffer(EntityManagerInterface $entityManager, Request $request, string $id)
    {
        $offer = $entityManager->getRepository(Offer::class)->find($id);
        $entityManager->remove($offer);
        $entityManager->flush();

            return $this->redirectToRoute('app_offers');
    }
}