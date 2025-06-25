<?php

namespace App\GraphQL\Mutations;

use App\Repositories\OrderRepository;
use GraphQL\Type\Definition\Type;
use Exception;

class OrderMutation
{
    public static function getMutations(array $types): array
    {
        return [
            'createOrder' => [
                'type' => $types['order'],
                'args' => [
                    'input' => [
                        'type' => Type::nonNull($types['orderInput'])
                    ]
                ],
                'resolve' => function ($rootValue, array $args) {
                    try {
                        $input = $args['input'];
                        
                        if (!isset($input['items']) || !isset($input['currency'])) {
                            throw new Exception('Missing required fields: items and currency');
                        }
                        
                        $repository = new OrderRepository();
                        $order = $repository->createOrder($input['items'], $input['currency']);
                        
                        if (!$order) {
                            throw new Exception('Failed to create order');
                        }
                        
                        return $order;
                    } catch (Exception $error) {
                        error_log("Order mutation error: " . $error->getMessage());
                        throw new Exception('Error creating order: ' . $error->getMessage());
                    }
                }
            ]
        ];
    }
}