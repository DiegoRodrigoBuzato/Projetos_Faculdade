<?php
// php/auth/logout.php
session_start();
session_unset();
session_destroy();

// Limpar cookie de sessão
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

header("Location: ../index.html?msg=logout_sucesso");
exit();
?>