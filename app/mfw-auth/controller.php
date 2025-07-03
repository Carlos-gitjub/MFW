<?php
// mfw-auth/controller.php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../mfw-url/helpers.php';
require_once __DIR__ . '/../mfw-security/functions.php';

function mfw_auth_logout_controller(): void
{
    // Obtener el token CSRF del formulario POST y validarlo
    $input = filter_input_array(INPUT_POST, [
        'csrf_token' => FILTER_UNSAFE_RAW
    ]);
    $csrf_token = $input['csrf_token'] ?? null;
    mfw_csrf_validate_and_respond($csrf_token);
    
    mfw_auth_logout();
    header('Location: ' . mfw_url('/'));
    exit;
}
