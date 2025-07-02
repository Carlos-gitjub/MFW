<?php
// mfw-db/connection.php

declare(strict_types=1);

/**
 * Devuelve una conexión PDO a la base de datos
 *
 * @return PDO
 */
function mfw_db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $host = getenv('MYSQL_HOST');
        $db   = getenv('MYSQL_DATABASE');
        $user = getenv('MYSQL_USER');
        $pass = getenv('MYSQL_PASSWORD');

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        $pdo = new PDO(
            $dsn,
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    return $pdo;
}

