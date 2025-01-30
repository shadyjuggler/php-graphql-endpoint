<?php

namespace App\Controller;

use GraphQL\GraphQL as GraphQLCore;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

use App\Database\Connection;
use \PDO;

class GraphQL
{
    public static function index()
    {

        Connection::init();
        $pdo = Connection::pdo();

        $userType = new ObjectType([
            'name' => 'User',
            'fields' => [
                'id' => Type::int(),
                'name' => Type::string(),
                'email' => Type::string(),
            ]
        ]);

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'users' => [
                    'type' => Type::listOf($userType),
                    'resolve' => function () use ($pdo) {
                        $stmt = $pdo->query("SELECT * FROM users");
                        return $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                ],
                'user' => [
                    'type' => $userType,
                    'args' => [
                        'id' => Type::nonNull(Type::int())
                    ],
                    'resolve' => function ($root, $args) use ($pdo) {
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                        $stmt->execute([$args['id']]);
                        return $stmt->fetch(PDO::FETCH_ASSOC);
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
