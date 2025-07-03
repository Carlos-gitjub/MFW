<?php
// mfw-security/functions.php

declare(strict_types=1);

require_once __DIR__ . '/../mfw-session/session.php';

/**
 * Genera un token CSRF solo si no existe en la sesión
 * El token se genera una vez por sesión y se reutiliza
 */
function mfw_csrf_generate(): string {
    // Si ya existe un token en la sesión, lo reutilizamos
    if (mfw_session_has('csrf_token')) {
        return mfw_session_get('csrf_token');
    }

    // Solo generar un nuevo token si no existe
    $token = bin2hex(random_bytes(32));
    mfw_session_set('csrf_token', $token);
    mfw_session_set('csrf_token_time', time());
    return $token;
}

/**
 * Obtiene el token CSRF actual de la sesión sin regenerarlo
 */
function mfw_csrf_get_token(): string {
    return mfw_session_get('csrf_token', '');
}

/**
 * Verifica si el token CSRF enviado es válido
 */
function mfw_csrf_validate(?string $token): bool {
    return mfw_session_has('csrf_token') &&
           hash_equals(mfw_session_get('csrf_token'), $token ?? '');
}

/**
 * Imprime un input hidden con el token CSRF actual (sin regenerarlo)
 */
function mfw_csrf_input(): void {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(mfw_csrf_get_token()) . '">';
}

/**
 * Valida el token CSRF y responde con error 403 si es inválido
 * Para usar en controladores POST
 */
function mfw_csrf_validate_and_respond(?string $token): bool {
    if (!mfw_csrf_validate($token)) {
        http_response_code(403);
        exit('CSRF token inválido o ausente.');
    }
    return true;
}
