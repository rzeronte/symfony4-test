<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\Rest\Product;

use App\Application\Command\CreateProduct\CreateProductCommand;
use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreateProductPage extends ApiCommandPage
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), false);

        $id = $payload->id ?? Uuid::uuid4()->toString();

        $this->dispatch(
            new CreateProductCommand(
                $id,
                $payload->name ?? '',
                $payload->categoryId ?? null,
                $payload->price ?? 0,
                $payload->currency ?? '',
                $payload->featured ?? false,
            )
        );

        return new JsonResponse(['id' => $id], Response::HTTP_OK);
    }
}
