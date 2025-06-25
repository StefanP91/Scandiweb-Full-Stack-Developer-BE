<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeItemType
{
    private static ?ObjectType $type = null;

    public static function getType(): ObjectType
    {
        if (self::$type === null) {
            self::$type = new ObjectType([
                'name' => 'AttributeItem',
                'fields' => [
                    'id' => Type::id(),
                    'value' => Type::string(),
                    'displayValue' => Type::string(),
                    'typename' => Type::string(),
                ]
            ]);
        }
        
        return self::$type;
    }
}