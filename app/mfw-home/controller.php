<?php
// mfw-home/controller.php

declare(strict_types=1);

require_once __DIR__ . '/view.php';

function mfw_home_controller(): string
{
    return mfw_home_view();
}
