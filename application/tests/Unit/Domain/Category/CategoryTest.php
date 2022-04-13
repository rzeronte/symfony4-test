<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Category;

use App\Domain\Category\Category;
use App\Domain\Category\ValueObject\CategoryDescription;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @throws AssertionFailedException
     */
    public function testCreateCategory()
    {
        $category = Category::create(
            CategoryId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc6'),
            CategoryName::from('name'),
            CategoryDescription::from('description')
        );

        $this->assertEquals('2ee49aa0-b085-4376-9bcd-a962aead4fc6', $category->getId()->value());
        $this->assertEquals('name', $category->getName()->value());
        $this->assertEquals('description', $category->getDescription()->value());
        $this->assertEquals('2ee49aa0-b085-4376-9bcd-a962aead4fc6', $category->getId());

        $this->assertEquals(json_encode($category->jsonSerialize()), json_encode([
            'id' => '2ee49aa0-b085-4376-9bcd-a962aead4fc6',
            'name' => 'name',
            'description' => 'description',
        ]));
    }

    /**
     * @throws AssertionFailedException
     */
    public function testCreateCategoryWithInvalidName()
    {
        $this->expectException(InvalidArgumentException::class);
        Category::create(
            CategoryId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc6'),
            CategoryName::from(''),
            CategoryDescription::from('description')
        );
    }

    /**
     * @throws AssertionFailedException
     */
    public function testCreateCategoryWithInvalidDescription()
    {
        $this->expectException(InvalidArgumentException::class);
        Category::create(
            CategoryId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc6'),
            CategoryName::from('name'),
            CategoryDescription::from('')
        );
    }
}
