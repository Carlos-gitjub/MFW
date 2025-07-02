<?php
// mfw-login/model.php

declare(strict_types=1);

/**
 * Verifica las credenciales del usuario
 */
function mfw_login_verify(string $username, string $password): bool
{
    $db = mfw_db();

    $stmt = $db->prepare('SELECT password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user) {
        return false;
    }

    // Aquí asume que la contraseña está hasheada con password_hash()
    return password_verify($password, $user['password']);
}
