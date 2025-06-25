<?php

namespace App\Models\Categories;

use App\Models\Abstracts\Category;

class ClothesCategory extends Category
{
    public function __construct()
    {
        parent::__construct('clothes', 'Category');
    }

    public function getDisplayInfo(): string
    {
        return "Clothes Category";
    }
}