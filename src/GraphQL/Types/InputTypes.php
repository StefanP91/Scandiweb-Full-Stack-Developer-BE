<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class InputTypes
{
    private InputObjectType $attributeInputType;
    private InputObjectType $orderItemInputType;
    private InputObjectType $orderInputType;

    public function __construct()
    {
        $this->attributeInputType = new InputObjectType([
            'name' => 'AttributeInput',
            'fields' => [
                'name' => Type::nonNull(Type::string()),
                'value' => Type::nonNull(Type::string())
            ]
        ]);

        $this->orderItemInputType = new InputObjectType([
            'name' => 'OrderItemInput',
            'fields' => [
                'productId' => Type::nonNull(Type::id()),
                'quantity' => Type::nonNull(Type::int()),
                'price' => Type::nonNull(Type::float()), 
                'selectedAttributes' => Type::listOf($this->attributeInputType)  
            ]
        ]);

        $this->orderInputType = new InputObjectType([
            'name' => 'OrderInput',
            'fields' => [
                'items' => Type::nonNull(Type::listOf(Type::nonNull($this->orderItemInputType))),
                'currency' => Type::nonNull(Type::string())
            ]
        ]);
    }

    public function getAttributeInputType(): InputObjectType
    {
        return $this->attributeInputType;
    }

    public function getOrderItemInputType(): InputObjectType
    {
        return $this->orderItemInputType;
    }

    public function getOrderInputType(): InputObjectType
    {
        return $this->orderInputType;
    }
}