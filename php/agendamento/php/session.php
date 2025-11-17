<?php
// php/includes/session.php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// Verifica se está logado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['user_agent'])) {
    header("Location: ../index.html");
    exit();
}

// Verifica integridade da sessão
if ($_SESSION['user_agent'] !== md5($_SERVER['HTTP_USER_AGENT'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.html");
    exit();
}

// Timeout de sessão (30 minutos)
$timeout_duration = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../index.html?erro=sessao_expirada");
    exit();
}

$_SESSION['last_activity'] = time();

// Token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function verificarCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>