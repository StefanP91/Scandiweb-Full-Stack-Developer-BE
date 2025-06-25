<?php

namespace App\Models\Categories;

use App\Models\Abstracts\Category;

class TechCategory extends Category
{
    public function __construct()
    {
        parent::__construct('tech', 'Category');
    }

    public function getDisplayInfo(): string
    {
        return "Tech Products";
    }
}