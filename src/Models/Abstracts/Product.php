<?php

namespace App\Models\Abstracts;

abstract class Product
{
    protected string $id;
    protected string $name;
    protected bool $inStock;
    protected array $gallery;
    protected string $description;
    protected string $category;
    protected string $brand;
    protected array $prices;
    protected array $attributes;
    protected string $typename;

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
        string $typename
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->inStock = $inStock;
        $this->gallery = $gallery;
        $this->description = $description;
        $this->category = $category;
        $this->brand = $brand;
        $this->prices = $prices;
        $this->attributes = $attributes;
        $this->typename = $typename;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    
    public function isInStock(): bool
    {
        return $this->inStock;
    }
    
    public function getGallery(): array
    {
        return $this->gallery;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getTypeName(): string
    {
        return $this->typename;
    }

    abstract public function getProductDetails(): array;
}