<?php

namespace App\Controller;

use App\Dto\RepositoryQueryDto;
use App\Service\RepositoryDiscoveryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RepositoryController extends AbstractController
{
    #[Route('/api/repositories', name: 'api_repositories', methods: ['GET'])]
    public function list(
        Request $request,
        ValidatorInterface $validator,
        RepositoryDiscoveryService $service,
    ): JsonResponse {
        $dto = new RepositoryQueryDto();

        if ($request->query->has('limit')) {
            $dto->limit = (int) $request->query->get('limit');
        }
        if ($request->query->has('createdFrom')) {
            $dto->createdFrom = (string) $request->query->get('createdFrom');
        }
        if ($request->query->has('language')) {
            $lang = trim((string) $request->query->get('language'));
            $dto->language = $lang !== '' ? $lang : null;
        }

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }

            return $this->json(['error' => 'Validation failed', 'details' => $messages], 400);
        }

        $result = $service->getPopularRepositories($dto->limit, $dto->createdFrom, $dto->language);

        return $this->json($result);
    }
}
