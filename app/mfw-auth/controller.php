<?php
// mfw-auth/controller.php

declare(strict_types=1);

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../mfw-url/helpers.php';

function mfw_auth_logout_controller(): void
{
    mfw_auth_logout();
    header('Location: ' . mfw_url('/'));
    exit;
}
