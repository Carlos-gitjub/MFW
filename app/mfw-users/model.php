<?php
// mfw-users/model.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-db/connection.php';

/**
 * Registra un nuevo usuario en la base de datos
 */
function mfw_user_register(string $username, string $password): bool
{
    $db = mfw_db();

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        return $stmt->execute([$username, $hash]);
    } catch (PDOException $e) {
        // Puede deberse a username duplicado
        return false;
    }
}

/**
 * Comprueba si un usuario ya existe
 */
function mfw_user_exists(string $username): bool
{
    $db = mfw_db();

    $stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->execute([$username]);

    return $stmt->fetch() !== false;
}
