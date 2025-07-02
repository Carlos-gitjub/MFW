<?php
// mfw-movies-streaming-services/view.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-theme/theme.php';
require_once __DIR__ . '/../mfw-url/helpers.php';

/**
 * Vista principal para el módulo
 */
function mfw_movies_streaming_services_view(string $title, array $results, string $region): string
{

    $header = mfw_theme_header('Where to watch?', [
        'css' => [ mfw_url('/mfw-movies-streaming-services/style.css') ],
        'js'  => [
            ['src' => mfw_url('/mfw-movies-streaming-services/region-selector.js'), 'attrs' => ['defer' => true]],
            ['src' => mfw_url('/mfw-movies-streaming-services/single-movie-search.js'), 'attrs' => ['defer' => true]],
        ],
    ]);
    

    ob_start();
    require __DIR__ . '/template.php';
    $content = ob_get_clean();

    $footer = mfw_theme_footer();

    return $header . $content . $footer;
}

function mfw_movies_streaming_services_advanced_view(): string
{
    $header = mfw_theme_header('Where to watch? - Advanced', [
        'css' => [ mfw_url('/mfw-movies-streaming-services/style.css') ]
    ]);
    
    ob_start();
    require __DIR__ . '/template-advanced.php';
    $content = ob_get_clean();

    $footer = mfw_theme_footer();

    return $header . $content . $footer;
}

function mfw_movies_streaming_services_subscription_most_view(string $region): string
{
    $header = mfw_theme_header('Where to watch? - Subscription Most', [
        'css' => [mfw_url('/mfw-movies-streaming-services/style.css')],
        'js'  => [
            ['src' => mfw_url('/mfw-movies-streaming-services/region-selector.js'), 'attrs' => ['defer' => true]],
            ['src' => mfw_url('/mfw-movies-streaming-services/subscription-most.js'), 'attrs' => ['defer' => true]]
        ]
    ]);
    
    
    ob_start();
    require __DIR__ . '/template-subscription-most.php';
    $content = ob_get_clean();

    $footer = mfw_theme_footer();

    return $header . $content . $footer;
}

function mfw_movies_streaming_services_subscription_most_result_view(array $platformDetails, string $region): string
{
    ob_start();
    require __DIR__ . '/template-partial-subscription-most-result.php';
    return ob_get_clean();
}
