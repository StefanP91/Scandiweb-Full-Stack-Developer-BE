<?php

namespace App\GraphQL\Queries;

use App\Repositories\ProductRepository;
use GraphQL\Type\Definition\Type;

class ProductQuery
{
    public static function getQueries(array $types): array
    {
        return [
            'products' => [
                'type' => Type::listOf($types['product']),
                'args' => [
                    'category' => [
                        'type' => Type::string(),
                        'description' => 'Filter products by category'
                    ]
                ],
                'resolve' => function ($rootValue, array $args): array {
                    $repository = new ProductRepository();
                    
                    if (isset($args['category'])) {
                        return $repository->getByCategory($args['category']);
                    } else {
                        return $repository->getAllProducts();
                    }
                }
            ]
        ];
    }
}