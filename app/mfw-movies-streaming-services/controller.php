<?php
// mfw-movies-streaming-services/controller.php

declare(strict_types=1);

require_once __DIR__ . '/view.php';
require_once __DIR__ . '/../mfw-tmdb-api/functions.php';
require_once __DIR__ . '/../mfw-watchmode-api/functions.php';
require_once __DIR__ . '/../mfw-auth/auth.php';
require_once __DIR__ . '/../mfw-security/functions.php';

/**
 * Controlador principal para /where-to-watch
 */
function mfw_movies_streaming_services_controller(): string
{
    mfw_auth_require();

    $title = trim($_GET['title'] ?? '');
    $region = strtoupper(trim($_GET['region'] ?? 'ES'));

    $results = [];

    if ($title !== '') {
        $tmdb = mfw_tmdb_api_search_movie($title);
        if ($tmdb) {
            $imdb_id = mfw_tmdb_api_get_imdb_id($tmdb['tmdb_id']);
            if ($imdb_id) {
                $watchmode = mfw_watchmode_api_search_by_imdb_id($imdb_id);
                if ($watchmode && isset($watchmode['id'])) {
                    $sources = mfw_watchmode_api_get_streaming_sources($watchmode['id'], $region);
                    $results = [
                        'movie' => [
                            'title' => $watchmode['name'] ?? $tmdb['title'],
                            'year' => $watchmode['year'] ?? $tmdb['release_date'] ?? null
                        ],
                        'sources' => $sources
                    ];
                }
            }
        }
    }

    return mfw_movies_streaming_services_view($title, $results, $region);
}

function mfw_movies_streaming_services_advanced_controller(): string
{
    mfw_auth_require();

    return mfw_movies_streaming_services_advanced_view();
}

function mfw_movies_streaming_services_subscription_most_controller(): string
{
    mfw_auth_require();

    $region = strtoupper(trim($_GET['region'] ?? 'ES'));

    return mfw_movies_streaming_services_subscription_most_view($region);
}

function mfw_movies_streaming_services_lookup_controller(): string
{
    header('Content-Type: application/json');
    mfw_auth_require();

    $title = trim($_GET['title'] ?? '');
    if ($title === '') {
        return json_encode(['success' => false]);
    }

    $movie = mfw_tmdb_api_search_movie($title);
    if (!$movie) {
        return json_encode(['success' => false]);
    }

    // Add year field extracted from release_date
    if (isset($movie['release_date']) && $movie['release_date']) {
        $movie['year'] = substr($movie['release_date'], 0, 4);
    } else {
        $movie['year'] = 'N/A';
    }

    return json_encode(['success' => true, 'movie' => $movie]);
}

function mfw_movies_streaming_services_subscription_most_result_controller(): string
{
    mfw_auth_require();

    $input = json_decode(file_get_contents('php://input'), true);
    $csrf_token = $input['csrf_token'] ?? null;
    
    // Validar CSRF token usando la nueva función
    mfw_csrf_validate_and_respond($csrf_token);

    $movies = $input['movies'] ?? [];
    $region = strtoupper(trim($_GET['region'] ?? 'ES'));

    if (!is_array($movies) || count($movies) === 0) {
        return '<div class="alert alert-danger">❌ Invalid movie list.</div>';
    }

    $platformDetails = [];

    foreach ($movies as $movie) {
        if (!isset($movie['tmdb_id'], $movie['title'], $movie['release_date'])) continue;

        $imdb_id = mfw_tmdb_api_get_imdb_id($movie['tmdb_id']);
        if (!$imdb_id) continue;

        $watchmode = mfw_watchmode_api_search_by_imdb_id($imdb_id);
        if (!$watchmode || !isset($watchmode['id'])) continue;

        $sources = mfw_watchmode_api_get_streaming_sources($watchmode['id'], $region);
        if (!is_array($sources)) continue;

        foreach ($sources as $source) {
            if ($source['type'] !== 'sub') continue;

            $name = $source['name'];
            if (!isset($platformDetails[$name])) {
                $platformDetails[$name] = ['count' => 0, 'movies' => []];
            }
            $platformDetails[$name]['count']++;
            
            // Add movie with year field extracted from release_date
            $platformDetails[$name]['movies'][] = [
                'title' => $movie['title'],
                'release_date' => $movie['release_date'],
                'year' => isset($movie['release_date']) && $movie['release_date'] ? substr($movie['release_date'], 0, 4) : 'N/A'
            ];
        }
    }

    // Ordenar por mayor número de títulos
    uasort($platformDetails, fn($a, $b) => $b['count'] <=> $a['count']);

    return mfw_movies_streaming_services_subscription_most_result_view($platformDetails, $region);
}