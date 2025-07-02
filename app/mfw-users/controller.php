<?php
// mfw-users/controller.php

declare(strict_types=1);

require_once __DIR__ . '/view.php';
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../mfw-url/helpers.php';

/**
 * Muestra el formulario de registro
 */
function mfw_user_register_controller(): string
{
    // Si ya está logueado, redirigir a la página principal
    if (mfw_auth_is_logged_in()) {
        header('Location: ' . mfw_url('/'));
        exit;
    }

    return mfw_user_register_view();
}

/**
 * Procesa el envío del formulario de registro
 */
function mfw_user_register_submit_controller(): string
{
    $input = filter_input_array(INPUT_POST, [
        'username' => FILTER_UNSAFE_RAW,
        'password' => FILTER_UNSAFE_RAW,
    ]);

    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';

    if ($username === '' || $password === '') {
        return mfw_user_register_view_with_error('Usuario y contraseña son obligatorios.');
    }

    if (mfw_user_exists($username)) {
        return mfw_user_register_view_with_error('Ese nombre de usuario ya está en uso.');
    }

    if (!mfw_user_register($username, $password)) {
        return mfw_user_register_view_with_error('No se pudo crear el usuario.');
    }

    header('Location: ' . mfw_url('/login'));
    exit;
}
