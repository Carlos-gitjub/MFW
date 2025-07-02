<?php
// mfw-watchmode-api/functions.php

declare(strict_types=1);

function mfw_watchmode_api_search_by_imdb_id(string $imdb_id): ?array {
    static $api_key = null;
    if ($api_key === null) {
        $config = require __DIR__ . '/config.php';
        $api_key = $config['watchmode_api_key'] ?? null;
    }

    if (!$api_key) {
        error_log("Watchmode API key no configurada.");
        return null;
    }

    $url = "https://api.watchmode.com/v1/search/?apiKey=$api_key&search_field=imdb_id&search_value=$imdb_id";
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $json = file_get_contents($url, false, $context);

    if ($json === false) {
        error_log("Error al conectar con la API de Watchmode (búsqueda por IMDb ID).");
        return null;
    }

    $data = json_decode($json, true);
    return $data['title_results'][0] ?? null;
}

function mfw_watchmode_api_get_streaming_sources(int $watchmode_id, string $region): ?array {
    static $api_key = null;

    if ($api_key === null || $region === null) {
        $config = require __DIR__ . '/config.php';
        $api_key = $config['watchmode_api_key'] ?? null;
    }

    if (!$api_key) {
        error_log("Watchmode API key no configurada.");
        return null;
    }

    $region = strtoupper(trim($region));

    $url = "https://api.watchmode.com/v1/title/$watchmode_id/sources/?apiKey=$api_key&regions=$region";
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $json = file_get_contents($url, false, $context);

    if ($json === false) {
        error_log("Error al obtener fuentes desde la API de Watchmode.");
        return null;
    }

    $data = json_decode($json, true);
    return is_array($data) ? $data : null;
}
