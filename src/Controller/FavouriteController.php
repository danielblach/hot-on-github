<?php

namespace App\Controller;

use App\Repository\FavouriteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class FavouriteController extends AbstractController
{
    #[Route('/api/favourites', name: 'api_favourites_list', methods: ['GET'])]
    public function list(FavouriteRepository $repo): JsonResponse
    {
        return $this->json(['items' => $repo->all()]);
    }

    #[Route('/api/favourites/{repoId}', name: 'api_favourites_add', methods: ['POST'])]
    public function add(int $repoId, FavouriteRepository $repo): JsonResponse
    {
        $repo->add($repoId);
        return $this->json(['repoId' => $repoId, 'isFavourite' => true]);
    }

    #[Route('/api/favourites/{repoId}', name: 'api_favourites_remove', methods: ['DELETE'])]
    public function remove(int $repoId, FavouriteRepository $repo): JsonResponse
    {
        $repo->remove($repoId);
        return $this->json(['repoId' => $repoId, 'isFavourite' => false]);
    }
}
