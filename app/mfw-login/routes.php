<?php
// mfw-login/routes.php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../mfw-router/register.php';

return [
    mfw_register('GET', '/login', 'mfw_login_controller'),
    mfw_register('POST', '/login/check', 'mfw_login_check_controller')
];
