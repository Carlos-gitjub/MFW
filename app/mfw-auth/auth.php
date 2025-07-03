<?php
// mfw-auth/auth.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-session/session.php';

function mfw_auth_set_user(string $username): void
{
    mfw_session_set('user', $username);
}

function mfw_auth_logout(): void
{
    mfw_session_destroy();
}

function mfw_auth_get_username(): ?string
{
    return mfw_session_get('user');
}

function mfw_auth_is_logged_in(): bool
{
    return mfw_auth_get_username() !== null;
}

function mfw_auth_require(): void
{
    if (!mfw_auth_is_logged_in()) {
        header('Location: ' . mfw_url('/login'));
        exit;
    }
}
