<?php

namespace App\Models\Products;

use App\Models\Abstracts\Product;

class ClothingProduct extends Product
{
    private string $size;
    private string $color;

    public function __construct(
        string $id,
        string $name,
        bool $inStock,
        array $gallery,
        string $description,
        string $category,
        string $brand,
        array $prices,
        array $attributes,
        string $typename,
        string $size,
        string $color
    ) {
        parent::__construct(
            $id, 
            $name, 
            $inStock, 
            $gallery, 
            $description, 
            $category, 
            $brand, 
            $prices, 
            $attributes, 
            $typename
        );
        $this->size = $size;
        $this->color = $color;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}