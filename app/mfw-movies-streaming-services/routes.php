<?php
// movies-streaming-services/routes.php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../mfw-router/register.php';

return [
    mfw_register('GET', '/where-to-watch', 'mfw_movies_streaming_services_controller'),
    mfw_register('GET', '/where-to-watch/advanced', 'mfw_movies_streaming_services_advanced_controller'),
    mfw_register('GET', '/where-to-watch/advanced/subscription-most', 'mfw_movies_streaming_services_subscription_most_controller'),
    mfw_register('GET', '/where-to-watch/advanced/tmdb-lookup', 'mfw_movies_streaming_services_lookup_controller'),
    mfw_register('POST', '/where-to-watch/advanced/subscription-most/result', 'mfw_movies_streaming_services_subscription_most_result_controller')
];
