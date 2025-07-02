<?php
// mfw-auth/routes.php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../mfw-router/register.php';

return [
    mfw_register('GET', '/logout', 'mfw_auth_logout_controller'),
];
