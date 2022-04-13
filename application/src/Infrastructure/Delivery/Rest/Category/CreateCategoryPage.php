<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\Rest\Category;

use App\Application\Command\CreateCategory\CreateCategoryCommand;
use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreateCategoryPage extends ApiCommandPage
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), false);

        $id = $payload->id ?? Uuid::uuid4()->toString();

        $this->dispatch(
            new CreateCategoryCommand(
                $id,
                $payload->name ?? '',
                $payload->description ?? '',
            )
        );

        return new JsonResponse(['id' => $id], Response::HTTP_OK);
    }
}
