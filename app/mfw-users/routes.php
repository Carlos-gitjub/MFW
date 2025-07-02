<?php
// mfw-users/routes.php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../mfw-router/register.php';

return [
    mfw_register('GET', '/register', 'mfw_user_register_controller'),
    mfw_register('POST', '/register', 'mfw_user_register_submit_controller'),
];
