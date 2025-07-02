<?php
// mfw-users/install.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-db/connection.php';

function mfw_users_install(): void
{
    $db = mfw_db();

    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
}
