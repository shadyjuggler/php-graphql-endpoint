<?php

namespace App\Controller;

use GraphQL\GraphQL as GraphQLCore;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class GraphQL
{
    public static function index()
    {

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'hello' => [
                    'type' => Type::string(),
                    'resolve' => function () {
                        return 'Hello, GraphQL!';
                    }
                ]
            ]
        ]);

        $schema = new Schema([
            'query' => $queryType,
        ]);

        try {
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            $query = $input['query'] ?? '';
            $variables = $input['variables'] ?? null;

            $result = GraphQLCore::executeQuery($schema, $query, null, null, $variables);
            $output = $result->toArray();
        } catch (\Exception $e) {
            $output = ['error' => $e->getMessage()];
        }

        header('Content-Type: application/json');
        return json_encode($output);
    }
}
