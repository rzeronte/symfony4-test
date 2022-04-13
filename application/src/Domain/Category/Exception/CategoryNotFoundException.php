<?php

declare(strict_types=1);

namespace App\Domain\Category\Exception;

use App\Shared\Exception\DomainException;
use Symfony\Component\HttpFoundation\Response;

final class CategoryNotFoundException extends DomainException
{
    public static function create(string $id = ''): self
    {
        return new self(sprintf('Category %s not found', $id), Response::HTTP_NOT_FOUND);
    }
}
