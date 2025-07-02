<?php
// mfw-menu/view.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-auth/auth.php';
require_once __DIR__ . '/../mfw-url/helpers.php';

function mfw_menu_render(): string
{
    $isLogged = mfw_auth_is_logged_in();

    $relativePath = mfw_current_relative_path();

    // Decidir qué plantilla usar
    if (!$isLogged && $relativePath === '/') {
        $template = __DIR__ . '/template-guest.php';
    } elseif ($isLogged) {
        $template = __DIR__ . '/template-user.php';
    } else {
        $template = __DIR__ . '/template-default.php';
    }

    ob_start();
    require $template;
    return ob_get_clean();
}
