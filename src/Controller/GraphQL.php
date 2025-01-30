<?php

namespace App\Controller;

class GraphQL {
    public static function index() {
        return json_encode(['success' => true]);
    }
}