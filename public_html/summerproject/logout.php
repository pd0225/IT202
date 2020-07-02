<?php
include_once(__DIR__."/partials/header.php");
session_unset();
session_destroy();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
Common::flash("You have successfully logged out");
?>