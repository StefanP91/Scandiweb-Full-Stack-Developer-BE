<?php

namespace App\GraphQL\Queries;

use App\Models\Categories\AllCategory;
use App\Models\Categories\ClothesCategory; 
use App\Models\Categories\TechCategory;
use GraphQL\Type\Definition\Type;

class CategoryQuery
{
    public static function getQueries(array $types): array
    {
        return [
            'categories' => [
                'type' => Type::listOf($types['category']),
                'resolve' => function (): array {
                    $categories = [
                        new AllCategory(),
                        new ClothesCategory(),
                        new TechCategory()
                    ];
                    
                    return $categories;
                }
            ]
        ];
    }
}