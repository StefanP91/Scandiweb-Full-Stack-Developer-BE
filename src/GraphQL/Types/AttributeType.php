<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeType
{
    private static ?ObjectType $type = null;

    public static function getType(): ObjectType
    {
        if (self::$type === null) {
            self::$type = new ObjectType([
                'name' => 'Attribute',
                'fields' => [
                    'id' => Type::id(),
                    'name' => Type::string(),
                    'typename' => Type::string(),
                    'items' => Type::listOf(AttributeItemType::getType()),
                    'type' => Type::string()
                ],
                'typename' => Type::string()
            ]);
        }
        
        return self::$type;
    }
}