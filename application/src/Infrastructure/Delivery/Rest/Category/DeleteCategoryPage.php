<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\Rest\Category;

use App\Application\Command\DeleteCategory\DeleteCategoryCommand;
use App\Shared\Infrastructure\Delivery\Rest\ApiCommandPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DeleteCategoryPage extends ApiCommandPage
{
    /** @throws \Throwable */
    public function __invoke(string $id, Request $request): JsonResponse
    {
        $result = $this->dispatch(new DeleteCategoryCommand($id));

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
