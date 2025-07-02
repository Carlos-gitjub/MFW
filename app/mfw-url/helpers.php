<?php
// mfw-url/helpers.php

declare(strict_types=1);

// Define la URL base (ej: /mfw-app) detectándola automáticamente,
// o permite sobrescribirla con una variable de entorno
if (!defined('BASE_URL')) {
    define('BASE_URL', getenv('MFW_BASE_URL') ?: rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
}

/**
 * Genera una URL absoluta dentro de la app
 *
 * @param string $path Ruta relativa (ej: /login)
 * @return string URL completa (ej: /mfw-app/login)
 */
function mfw_url(string $path): string
{
    return BASE_URL . $path;
}

/**
 * Devuelve la ruta relativa actual dentro de la app
 * (por ejemplo: /login, /dashboard, etc.)
 *
 * @return string
 */
function mfw_current_relative_path(): string
{
    $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return '/' . ltrim(str_replace(BASE_URL, '', $uriPath), '/');
}
