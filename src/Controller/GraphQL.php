<?php

namespace App\Controller;

use App\GraphQL\Schema;
use GraphQL\GraphQL as GraphQLBase;
use RuntimeException;
use Throwable;

class GraphQL
{
    public static function handle(): string
    {
        try {
            $schema = new Schema();
            
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
                
            }
            
            $input = json_decode($rawInput, true);
            $query = $input['query'] ?? null;
            $variableValues = $input['variables'] ?? null;
            
            if (empty($query)) {
                $query = $schema->getDefaultQuery();
            }
            
            $result = GraphQLBase::executeQuery(
                $schema->getSchema(),
                $query,
                null,
                null,
                $variableValues
            );
            
            $output = $result->toArray();                       
        } catch (Throwable $error) {
            error_log(sprintf(
                "GraphQL Error: %s\n%s",
                $error->getMessage(),
                $error->getTraceAsString()
            ));
            
            header('Content-Type: application/json; charset=UTF-8');
            
            return json_encode([
                'errors' => [[
                    'message' => $error->getMessage(),
                    'extensions' => [
                        'file' => $error->getFile(),
                        'line' => $error->getLine(),
                        'trace' => $error->getTraceAsString()
                    ]
                ]]
            ]);
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}