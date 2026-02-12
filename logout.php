<?php
session_start();

/* =========================
   Unset All Session Data
   ========================= */
$_SESSION = [];

/* =========================
   Destroy Session
   ========================= */
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

/* =========================
   Redirect to Login
   ========================= */
header("Location: login.php?logout=success");
exit();
