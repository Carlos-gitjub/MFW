<?php
// mfw-auth/auth.php

declare(strict_types=1);

require_once __DIR__ . '/session.php';

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
