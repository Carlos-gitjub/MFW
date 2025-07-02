<?php
// mfw-auth/session.php

declare(strict_types=1);

function mfw_auth_start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_secure' => isset($_SERVER['HTTPS']),
            'cookie_samesite' => 'Strict',
        ]);
    }
}

function mfw_auth_set_user(string $username): void
{
    mfw_auth_start_session();
    $_SESSION['user'] = $username;
}

function mfw_auth_logout(): void
{
    mfw_auth_start_session();
    $_SESSION = [];
    session_destroy();
}

function mfw_auth_get_username(): ?string
{
    mfw_auth_start_session();
    return $_SESSION['user'] ?? null;
}
