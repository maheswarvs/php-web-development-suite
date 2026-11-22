<?php
// Runs before every script (auto_prepend_file). Configures the session cookie only;
// the application still calls session_start() itself.
if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
          || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    session_set_cookie_params([
        'lifetime' => 0,          // cookie lasts until the browser closes
        'path'     => '/',
        'secure'   => $https,     // Secure on Render (HTTPS), still works on http://localhost
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}
