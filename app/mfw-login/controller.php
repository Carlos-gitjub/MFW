<?php
// mfw-login/controller.php

declare(strict_types=1);

require_once __DIR__ . '/view.php';
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../mfw-url/helpers.php';
require_once __DIR__ . '/../mfw-auth/session.php';

/**
 * Handles the login page request
 * 
 * @return string HTML content for the login page
 */
function mfw_login_controller(): string
{
    // Si ya está logueado, redirigir a la página principal
    if (mfw_auth_is_logged_in()) {
        header('Location: ' . mfw_url('/'));
        exit;
    }

    return mfw_login_view();
}

function mfw_login_check_controller(): string
{
    // 1. Recoger input de forma explícita y segura
    $input = filter_input_array(INPUT_POST, [
        'username' => FILTER_UNSAFE_RAW,
        'password' => FILTER_UNSAFE_RAW
    ]);

    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';

    // 2. Validación básica
    if ($username === '' || $password === '') {
        return mfw_login_view_with_error('Usuario y contraseña son obligatorios.');
    }

    // 3. Lógica de autenticación (modelo puro)
    if (mfw_login_verify($username, $password)) {
        // Establecer sesión
        mfw_auth_set_user($username);

        // 4. Login correcto → redirigir
        header('Location: ' . mfw_url('/'));
        exit;
    }

    // 5. Login fallido → mostrar error
    return mfw_login_view_with_error('Credenciales inválidas.');
}