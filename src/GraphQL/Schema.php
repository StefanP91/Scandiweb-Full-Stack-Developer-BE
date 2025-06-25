<?php

namespace App\GraphQL;

use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\OrderType;
use App\GraphQL\Types\InputTypes;
use App\GraphQL\Queries\CategoryQuery;
use App\GraphQL\Queries\ProductQuery;
use App\GraphQL\Mutations\OrderMutation;
use GraphQL\Type\Schema as GraphQLSchema;
use GraphQL\Type\Definition\ObjectType;

class Schema
{
    private GraphQLSchema $schema;
    private array $types;
    private InputTypes $inputTypes;

    public function __construct()
    {
        $this->inputTypes = new InputTypes();

        $this->types = [
            'category' => new CategoryType(),
            'product' => new ProductType(),
            'order' => new OrderType(),
            'orderInput' => $this->inputTypes->getOrderInputType(),
            'orderItemInput' => $this->inputTypes->getOrderItemInputType(),
            'attributeInput' => $this->inputTypes->getAttributeInputType()
        ];

        // Query Type Definition
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => array_merge(
                CategoryQuery::getQueries($this->types),
                ProductQuery::getQueries($this->types)
            )
        ]);

        // Mutation Type Definition
        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => OrderMutation::getMutations($this->types)
        ]);

        // Schema Definition
        $this->schema = new GraphQLSchema([
            'query' => $queryType,
            'mutation' => $mutationType
        ]);
    }

    public function getSchema(): GraphQLSchema
    {
        return $this->schema;
    }

    public function getDefaultQuery(): string
    {
        return '
            query {
                categories {
                    name
                    typename
                }
                products {
                    id
                    name
                    inStock
                    gallery
                    description
                    category
                    attributes {
                        id
                        items {
                            displayValue
                            value
                            id
                            typename
                        }
                        name
                        type
                        typename                        
                    }       
                    prices {
                        amount
                        currency {
                            label
                            symbol
                            typename
                        }
                        typename
                    }
                    brand
                    typename
                }
            }
        ';
    }
}