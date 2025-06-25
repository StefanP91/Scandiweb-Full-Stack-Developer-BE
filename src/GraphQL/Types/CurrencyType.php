<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CurrencyType
{
    private static ?ObjectType $type = null;

    public static function getType(): ObjectType
    {
        if (self::$type === null) {
            self::$type = new ObjectType([
                'name' => 'Currency',
                'fields' => [
                    'label' => Type::string(),
                    'symbol' => Type::string()
                ]
            ]);
        }

        return self::$type;
    }
}