<?php
// mfw-home/view.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-theme/theme.php';
require_once __DIR__ . '/../mfw-url/helpers.php';
require_once __DIR__ . '/../mfw-auth/auth.php';

function mfw_home_view(): string
{
    $header = mfw_theme_header('Home');

    ob_start();
    if (mfw_auth_is_logged_in()) {
        require __DIR__ . '/template-user.php';
    } else {
        require __DIR__ . '/template-guest.php';
    }
    $content = ob_get_clean();

    $footer = mfw_theme_footer();

    return $header . $content . $footer;
}