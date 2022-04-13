<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Product;

use App\Domain\Category\Category;
use App\Domain\Category\ValueObject\CategoryDescription;
use App\Domain\Category\ValueObject\CategoryId;
use App\Domain\Category\ValueObject\CategoryName;
use App\Domain\Product\Product;
use App\Domain\Product\ValueObject\ProductId;
use App\Domain\Product\ValueObject\ProductName;
use App\Domain\Product\ValueObject\ProductPrice;
use App\Shared\Application\ValueObject\Currency;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @throws AssertionFailedException
     */
    public function testCreateProduct()
    {
        $product = Product::create(
            ProductId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc6'),
            ProductName::from('name'),
            Category::create(
                CategoryId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc6'),
                CategoryName::from('name'),
                CategoryDescription::from('description')
            ),
            ProductPrice::from(1),
            Currency::from('USD'),
            false
        );

        $this->assertEquals('2ee49aa0-b085-4376-9bcd-a962aead4fc6', $product->getId()->value());
        $this->assertEquals('2ee49aa0-b085-4376-9bcd-a962aead4fc6', $product->getId());
        $this->assertEquals('name', $product->getName()->value());
        $this->assertEquals('name', $product->getName());
        $this->assertEquals(1, $product->getPrice()->value());
        $this->assertEquals('USD', $product->getCurrency());
        $this->assertEquals('2ee49aa0-b085-4376-9bcd-a962aead4fc6', $product->getCategory()->getId()->value());
        $this->assertFalse($product->getFeatured());

        $this->assertEquals(json_encode($product->jsonSerialize()), json_encode([
            'id' => '2ee49aa0-b085-4376-9bcd-a962aead4fc6',
            'name' => 'name',
            'categoryId' => [
                'id' => '2ee49aa0-b085-4376-9bcd-a962aead4fc6',
                'name' => 'name',
                'description' => 'description',
            ],
            'price' => 1,
            'currency' => 'USD',
            'featured' => false,
        ]));
    }

    /**
     * @throws AssertionFailedException
     */
    public function testCreateProductBadPrice()
    {
        $this->expectException(InvalidArgumentException::class);
        Product::create(
            ProductId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc7'),
            ProductName::from('name'),
            null,
            ProductPrice::from(0),
            Currency::from('USD'),
            false
        );

        $this->expectException(InvalidArgumentException::class);
        Product::create(
            ProductId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc7'),
            ProductName::from('name'),
            null,
            ProductPrice::from(1),
            Currency::from('USD'),
            false
        );
    }

    /**
     * @throws AssertionFailedException
     */
    public function testCreateProductWithBadCurrency()
    {
        $this->expectException(InvalidArgumentException::class);
        Product::create(
            ProductId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc7'),
            ProductName::from('name'),
            null,
            ProductPrice::from(1),
            Currency::from('BADCURRENCY'),
            false
        );
    }

    /**
     * @throws AssertionFailedException
     */
    public function testCreateProductWithBadName()
    {
        $this->expectException(InvalidArgumentException::class);
        Product::create(
            ProductId::from('2ee49aa0-b085-4376-9bcd-a962aead4fc7'),
            ProductName::from(''),
            null,
            ProductPrice::from(1),
            Currency::from('EUR'),
            false
        );
    }
}
