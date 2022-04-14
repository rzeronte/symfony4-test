<?php

declare(strict_types=1);

namespace App\Infrastructure\Delivery\Rest\Product;

use App\Application\Query\RetrieveFeaturedProducts\RetrieveFeaturedProductsQuery;
use App\Shared\Infrastructure\Delivery\Rest\ApiQueryPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RetrieveFeaturedProductsPage extends ApiQueryPage
{
    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->ask(
            new RetrieveFeaturedProductsQuery(
                $request->query->has('currency') ? $request->get('currency') : null,
                $request->query->has('page') ? $request->get('page') : null,
                $request->query->has('limit') ? $request->get('limit') : null,
            )
        );

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
