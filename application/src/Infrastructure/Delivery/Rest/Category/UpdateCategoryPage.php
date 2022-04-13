<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\Rest\Category;

use App\Application\Command\UpdateCategory\UpdateCategoryCommand;
use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UpdateCategoryPage extends ApiCommandPage
{
    /**
     * @throws Throwable
     */
    public function __invoke(string $id, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), false);

        $result = $this->dispatch(
            new UpdateCategoryCommand(
                $id,
                $payload->name ?? '',
                $payload->descriptin ?? ''
            )
        );

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
