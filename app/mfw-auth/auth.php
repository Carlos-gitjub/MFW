<?php
// mfw-auth/auth.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-session/session.php';
require_once __DIR__ . '/../mfw-url/helpers.php';

/**
 * Establece el usuario autenticado en la sesión
 */
function mfw_auth_set_user(string $username): void
{
    mfw_session_set('user', $username);
}

/**
 * Cierra la sesión del usuario actual
 */
function mfw_auth_logout(): void
{
    mfw_session_destroy();
}

/**
 * Obtiene el nombre de usuario de la sesión actual
 */
function mfw_auth_get_username(): ?string
{
    return mfw_session_get('user');
}

/**
 * Verifica si hay un usuario autenticado
 */
function mfw_auth_is_logged_in(): bool
{
    return mfw_auth_get_username() !== null;
}

/**
 * Requiere autenticación - redirige a login si no está autenticado
 */
function mfw_auth_require(): void
{
    if (!mfw_auth_is_logged_in()) {
        header('Location: ' . mfw_url('/login'));
        exit;
    }
}
