<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PriceType
{
    private static ?ObjectType $type = null;
    private static ?ObjectType $currencyType = null;

    public static function getType(): ObjectType
    {
        if (self::$type === null) {
            self::$type = new ObjectType([
                'name' => 'Price',
                'fields' => [
                    'amount' => Type::float(),
                    'currency' => self::getCurrencyType(),
                    'typename' => Type::string()
                ]
            ]);
        }

        return self::$type;
    }

    public static function getCurrencyType(): ObjectType
    {
        if (self::$currencyType === null) {
            self::$currencyType = new ObjectType([
                'name' => 'Currency',
                'fields' => [
                    'label' => Type::string(),
                    'symbol' => Type::string(),
                    'typename' => Type::string()
                ]
            ]);
        }

        return self::$currencyType;
    }
}