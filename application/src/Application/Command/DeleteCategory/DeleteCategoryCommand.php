<?php

declare(strict_types=1);

namespace App\Application\Command\DeleteCategory;

final class DeleteCategoryCommand
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
