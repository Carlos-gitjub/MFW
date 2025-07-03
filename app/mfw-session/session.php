<?php
// mfw-session/session.php

declare(strict_types=1);

/**
 * Inicia la sesión con configuración segura
 */
function mfw_session_start(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_secure' => isset($_SERVER['HTTPS']),
            'cookie_samesite' => 'Strict',
        ]);
    }
}

/**
 * Obtiene un valor de la sesión
 */
function mfw_session_get(string $key, $default = null)
{
    return $_SESSION[$key] ?? $default;
}

/**
 * Establece un valor en la sesión
 */
function mfw_session_set(string $key, $value): void
{
    $_SESSION[$key] = $value;
}

/**
 * Verifica si existe una clave en la sesión
 */
function mfw_session_has(string $key): bool
{
    return isset($_SESSION[$key]);
}

/**
 * Elimina una clave específica de la sesión
 */
function mfw_session_remove(string $key): void
{
    unset($_SESSION[$key]);
}

/**
 * Destruye completamente la sesión
 */
function mfw_session_destroy(): void
{
    $_SESSION = [];
    session_destroy();
}

/**
 * Regenera el ID de sesión (útil para prevenir session fixation)
 */
function mfw_session_regenerate(): void
{
    session_regenerate_id(true);
} 