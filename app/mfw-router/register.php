<?php
// mfw-router/register.php

declare(strict_types=1);

/**
 * Registers a new route in the application
 * 
 * @param string $method HTTP method (GET, POST, etc.)
 * @param string $path URL path to match
 * @param callable $handler Function to execute when route matches
 * @return array Route configuration array
 */
function mfw_register(string $method, string $path, callable $handler): array
{
    return [
        'method' => strtoupper($method),
        'path' => $path,
        'handler' => $handler,
    ];
}
