<?php
// index.php

declare(strict_types=1);

require_once __DIR__ . '/mfw-router/register.php';
require_once __DIR__ . '/mfw-router/dispatch.php';
require_once __DIR__ . '/mfw-security/functions.php';
require_once __DIR__ . '/mfw-session/session.php';

// Inicializar sesión de forma centralizada y segura
mfw_session_start();

// Generar token CSRF una vez por sesión
mfw_csrf_generate();

$routes = [];

// Registrar rutas de todos los módulos
foreach (glob(__DIR__ . '/mfw-*/routes.php') as $file) {
    $moduleRoutes = require $file;
    if (is_array($moduleRoutes)) {
        $routes = array_merge($routes, $moduleRoutes);
    }
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '/';

$response = mfw_dispatch($routes, $method, $path);

echo $response;
