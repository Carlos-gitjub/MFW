<?php
// mfw-url/helpers.php

declare(strict_types=1);

/**
 * Normalize path separators to forward slashes for URL consistency
 * 
 * @param string $path The path to normalize
 * @return string Normalized path with forward slashes
 */
function mfw_normalize_path(string $path): string
{
    return str_replace('\\', '/', $path);
}

/**
 * Get the base URL for the application
 * Automatically detects if the app is running at root (/) or in a subdirectory (e.g., /mfw-app/)
 * 
 * @return string The base URL with trailing slash removed, or '/' for root
 */
function mfw_get_base_url(): string
{
    $envBaseUrl = getenv('MFW_BASE_URL');
    
    if ($envBaseUrl !== false) {
        $baseUrl = rtrim(mfw_normalize_path($envBaseUrl), '/');
        return $baseUrl === '' ? '/' : $baseUrl;
    }
    
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
    $baseUrl = rtrim(mfw_normalize_path($scriptDir), '/');
    return $baseUrl === '' ? '/' : $baseUrl;
}

// Define the base URL constant if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', mfw_get_base_url());
}

/**
 * Generate an absolute URL within the application
 * Automatically handles both root (/) and subdirectory (e.g., /mfw-app/) deployments
 * 
 * @param string $path Relative path (e.g., /login)
 * @return string Complete URL (e.g., /login or /mfw-app/login depending on deployment)
 */
function mfw_url(string $path): string
{
    // Ensure path starts with a single slash
    $path = '/' . ltrim($path, '/');
    // If BASE_URL is '/', avoid double slash
    if (BASE_URL === '/') {
        return $path === '/' ? '/' : $path;
    }
    return BASE_URL . $path;
}

/**
 * Get the current relative path within the application
 * Works correctly regardless of whether the app is deployed at root or in a subdirectory
 * 
 * @return string Current relative path (e.g., /login, /dashboard, etc.)
 */
function mfw_current_relative_path(): string
{
    $uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return '/' . ltrim(str_replace(BASE_URL, '', $uriPath), '/');
}
