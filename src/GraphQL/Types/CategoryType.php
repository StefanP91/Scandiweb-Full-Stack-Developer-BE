<?php

namespace App\GraphQL\Types;

use App\Models\Abstracts\Category;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CategoryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Category',
            'fields' => [
                'name' => [
                    'type' => Type::string(),
                    'resolve' => function ($category) {
                        if ($category instanceof Category) {
                            return $category->getName();
                        }
                        
                        if (is_array($category) && isset($category['name'])) {
                            return $category['name'];
                        }
                        
                        return null;
                    }
                ],
                'typename' => [
                    'type' => Type::string(),
                    'resolve' => function () {
                        return 'Category';
                    }
                ]
            ],

            'resolveField' => function ($value, $args, $context, $info) {
                $method = 'get' . ucfirst($info->fieldName);
                if (method_exists($value, $method)) {
                    return $value->{$method}();
                }
                return null;
            }
        ];
        
        parent::__construct($config);
    }
}