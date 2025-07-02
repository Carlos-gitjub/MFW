<?php
// mfw-tmdb-api/functions.php

declare(strict_types=1);

function mfw_tmdb_api_search_movie(string $title): ?array {
    static $api_key = null;
    if ($api_key === null) {
        $config = require __DIR__ . '/config.php';
        $api_key = $config['tmdb_api_key'] ?? null;
    }

    if (!$api_key) {
        error_log("TMDb API key no configurada.");
        return null;
    }

    $query = urlencode($title);
    $url = "https://api.themoviedb.org/3/search/movie?api_key=$api_key&query=$query";

    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $json = file_get_contents($url, false, $context);

    if ($json === false) {
        error_log("Error al conectar con la API de TMDb.");
        return null;
    }

    $data = json_decode($json, true);
    if (!isset($data['results'][0])) return null;

    $movie = $data['results'][0]; //Results are returned in order of relevance (check oficial documentation of TMDb API).
    return [
        'tmdb_id'       => $movie['id'],
        'title'         => $movie['title'],
        'release_date'  => $movie['release_date'] ?? null
    ];
}

function mfw_tmdb_api_get_imdb_id(int $tmdb_id): ?string {
    static $api_key = null;
    if ($api_key === null) {
        $config = require __DIR__ . '/config.php';
        $api_key = $config['tmdb_api_key'] ?? null;
    }

    if (!$api_key) {
        error_log("TMDb API key no configurada.");
        return null;
    }

    $url = "https://api.themoviedb.org/3/movie/$tmdb_id/external_ids?api_key=$api_key";

    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $json = file_get_contents($url, false, $context);

    if ($json === false) {
        error_log("Error al obtener IMDb ID desde la API de TMDb.");
        return null;
    }

    $data = json_decode($json, true);
    return $data['imdb_id'] ?? null;
}
