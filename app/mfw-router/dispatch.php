<?php
// mfw-router/dispatch.php

declare(strict_types=1);

/**
 * Dispatches the request to the appropriate route handler
 * 
 * @param array $routes Array of registered routes
 * @param string $method HTTP method of the request
 * @param string $path Requested URL path
 * @return string Response content from the handler or 404 page
 */
function mfw_dispatch(array $routes, string $method, string $path): string
{
    foreach ($routes as $route) {
        if ($route['method'] === $method && $route['path'] === $path) {
            return $route['handler']();
        }
    }

    http_response_code(404);
    return '<h1>404 - Página no encontrada</h1>';
}
