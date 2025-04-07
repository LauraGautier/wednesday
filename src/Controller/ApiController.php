<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Offer;
use App\Entity\Candidacy;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface as SerializationSerializerInterface;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;

// Préfixe pour toutes les routes, ici les routes API
#[Route('/api', name: 'app_api_')]
#[OA\Response(
    response: 200,
    description: 'Returns the rewards of an user',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Offer::class, groups: ['full']))
    )
)]
#[OA\Tag (name: 'offers')]
#[Security(name: 'Bearer')]
final class ApiController extends AbstractController
{   // ROUTES POUR LES OFFRES
    // Route GET pour afficher toutes les offres
    #[Route('/offers', name: 'list', methods:['GET'])]
    public function list(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $offers = $em->getRepository(Offer::class)->findAll();
        $data = $serializer->serialize($offers, 'json', ['groups' => 'offer:read']);

        return new JsonResponse($data, 200, [], true);
    }
    // Route GET pour récupérer une offre grâce à son ID
    #[Route('/offers/{id}', name: 'show', methods:['GET'])]
    public function show(Offer $offer, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($offer, 'json', ['groups' => 'offer:read']);

        return new JsonResponse($data, 200, [], true);
    }
    // Route PUT pour mettre à jour tous les champs d'une offre
    #[Route('/offers/{id}', name: 'update', methods:['PUT'])]
    public function update(Offer $offer, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Offer::class, 'json', ['object_to_populate' => $offer]);
        $em->flush();

        return new JsonResponse(['message' => 'Offer updated!'], 200);
    }
    // Route PATCH pour mettre à jour un seul champ d'une offre
    #[Route('/offers/{id}', name: 'patch', methods:['PATCH'])]
    public function patch(Offer $offer, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Offer::class, 'json', ['object_to_populate' => $offer]);
        $em->flush();

        return new JsonResponse(['message' => 'Offer updated!'], 200);
    }
    // Route DELETE pour supprimer une offre
    #[Route('/offers/{id}', name: 'deleteOffer', methods:['DELETE'])]
    public function deleteOffer(Offer $offer, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($offer);
        $em->flush();

        return new JsonResponse(['message' => 'Offer deleted!'], 204);
    }
    // ROUTES POUR LES CANDIDATURES
    // Route GET pour afficher toutes les candidatures
    #[Route('/candidacies', name: 'candidacies', methods:['GET'])]
    public function candidacies(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $candidacies = $em->getRepository(Candidacy::class)->findAll();
        $data = $serializer->serialize($candidacies, 'json', ['groups' => 'offer:read']);

        return new JsonResponse($data, 200, [], true);
    }
    // Route GET pour afficher une candidature grâce à son ID
    #[Route('/candidacies/{id}', name: 'candidacy', methods:['GET'])]
    public function candidacy(Candidacy $candidacy, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($candidacy, 'json', ['groups' => 'offer:read']);

        return new JsonResponse($data, 200, [], true);
    }
    // Route PUT pour mettre à jour tous les champs d'une offre
    #[Route('/candidacies/{id}', name: 'updateCandidacy', methods:['PUT'])]
    public function updateCandidacy(Candidacy $candidacy, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Candidacy::class, 'json', ['object_to_populate' => $candidacy]);
        $em->flush();

        return new JsonResponse(['message' => 'Candidacy updated!'], 200);
    }
    // Route PATCH pour mettre à jour un seul champ d'une offre
    #[Route('/candidacies/{id}', name: 'patchCandidacy', methods:['PATCH'])]
    public function patchCandidacy(Candidacy $candidacy, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Candidacy::class, 'json', ['object_to_populate' => $candidacy]);
        $em->flush();

        return new JsonResponse(['message' => 'Candidacy updated!'], 200);
    }
    // Route DELETE pour supprimer une candidature
    #[Route('/candidacies/{id}', name: 'deleteCandidacy', methods:['DELETE'])]
    public function deleteCandidacy(Candidacy $candidacy, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($candidacy);
        $em->flush();

        return new JsonResponse(['message' => 'Candidacy deleted!'], 204);
    }
}
