<?php

namespace App\GraphQL\Types;

use App\Models\Abstracts\Product;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
    public function __construct()
    {
         $config = [
            'name' => 'Product',
            'fields' => [
                'id' => [
                    'type' => Type::id()
                ],
                'name' => [
                    'type' => Type::string()
                ],
                'inStock' => [
                    'type' => Type::boolean()
                ],
                'gallery' => [
                    'type' => Type::listOf(Type::string())
                ],
                'description' => [
                    'type' => Type::string(),
                ],
                'category' => [
                    'type' => Type::string()
                ],
                'brand' => [
                    'type' => Type::string()
                ],
                'prices' => [
                    'type' => Type::listOf($this->getPriceType())
                ],
                'attributes' => [
                    'type' => Type::listOf($this->getAttributeType())
                ],
                'typename' => [
                    'type' => Type::string()
                ]
            ]
        ];
        
        parent::__construct($config);
    }

    private function getPriceType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Price',
            'fields' => [
                'amount' => [
                    'type' => Type::float()
                ],
                'currency' => [
                    'type' => new ObjectType([
                        'name' => 'Currency',
                        'fields' => [
                            'label' => [
                                'type' => Type::string()
                            ],
                            'symbol' => [
                                'type' => Type::string()
                            ],
                            'typename' => [
                                'type' => Type::string(),
                                'resolve' => function ($currency) {
                                    return $currency['typename'] ?? 'Currency';
                                }
                            ]
                        ]
                    ])
                ],
                'typename' => [
                    'type' => Type::string()
                ]
            ]
        ]);
    }
    
    private function getAttributeType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Attribute',
            'fields' => [
                'id' => [
                    'type' => Type::string()
                ],
                'name' => [
                    'type' => Type::string()
                ],
                'items' => [
                    'type' => Type::listOf($this->getAttributeItemType())
                ],
                'type' => [
                    'type' => Type::string()
                ],
                'typename' => [
                    'type' => Type::string(),
                    'resolve' => function ($attribute) {
                        return $attribute['typename'] ?? 'Attribute';
                    }
                ]
            ]
        ]);
    }
    
    private function getAttributeItemType(): ObjectType
    {
        return new ObjectType([
            'name' => 'AttributeItem',
            'fields' => [
                'id' => [
                    'type' => Type::string()
                ],
                'value' => [
                    'type' => Type::string()
                ],
                'displayValue' => [
                    'type' => Type::string()
                ],
                'typename' => [
                    'type' => Type::string(),
                    'resolve' => function ($item) {
                        return $item['typename'];
                    }
                ]
            ]
        ]);
    }
}