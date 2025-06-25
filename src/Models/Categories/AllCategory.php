<?php

namespace App\Models\Categories;

use App\Models\Abstracts\Category;

class AllCategory extends Category
{
   public function __construct()
   {
      parent::__construct('all', 'Category');
   }

    public function getDisplayInfo(): string
    {
      return "All Categories";
    }
}