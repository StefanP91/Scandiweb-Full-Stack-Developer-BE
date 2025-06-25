<?php

namespace App\GraphQL\Types;

use App\Models\Abstracts\Order;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Order',
            'fields' => [
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($order) {
                        if ($order instanceof Order) {
                            return $order->getId();
                        }
                        return $order['id'] ?? null;
                    }
                ],
                'orderNumber' => [
                    'type' => Type::string(),
                    'resolve' => function ($order) {
                        if ($order instanceof Order) {
                            return $order->getOrderNumber();
                        }
                        return $order['orderNumber'] ?? null;
                    }
                ],
                'total' => [
                    'type' => Type::float(),
                    'resolve' => function ($order) {
                        if ($order instanceof Order) {
                            return $order->getTotal();
                        }
                        return $order['total'] ?? null;
                    }
                ],
                'currency' => [
                    'type' => Type::string(),
                    'resolve' => function ($order) {
                        if ($order instanceof Order) {
                            return $order->getCurrency();
                        }
                        return $order['currency'] ?? null;
                    }
                ],
                'createdAt' => [
                    'type' => Type::string(),
                    'resolve' => function ($order) {
                        if ($order instanceof Order) {
                            return $order->getCreatedAt();
                        }
                        return $order['createdAt'] ?? null;
                    }
                ]
            ]
        ];
        
        parent::__construct($config);
    }
}