<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\Rest\Product;

use App\Application\Query\RetrieveProducts\RetrieveProductsQuery;
use App\Shared\Infrastructure\Delivery\Rest\ApiQueryPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RetrieveProductsPage extends ApiQueryPage
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->ask(
            new RetrieveProductsQuery(
                null,
                $request->get('page'),
                $request->get('size'),
            )
        );

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
