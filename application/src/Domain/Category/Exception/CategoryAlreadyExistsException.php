<?php

declare(strict_types=1);

namespace App\Domain\Category\Exception;

use App\Shared\Exception\DomainException;
use Symfony\Component\HttpFoundation\Response;

final class CategoryAlreadyExistsException extends DomainException
{
    public static function create(string $id = ''): DomainException
    {
        return new self(sprintf('Category %s already exists', $id), Response::HTTP_CONFLICT);
    }
}
