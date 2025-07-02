<?php
// mfw-login/view.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-theme/theme.php';

function mfw_login_view(): string
{
    $header = mfw_theme_header('Login');

    ob_start();
    require __DIR__ . '/template.php';
    $content = ob_get_clean();

    $footer = mfw_theme_footer();

    return $header . $content . $footer;
}

function mfw_login_view_with_error(string $message): string
{
    $header = mfw_theme_header('Login');

    ob_start();
    $error = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    require __DIR__ . '/template.php';
    $content = ob_get_clean();

    $footer = mfw_theme_footer();

    return $header . $content . $footer;
}

