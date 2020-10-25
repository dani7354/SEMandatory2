<?php 
function get_new_token()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $token = bin2hex(random_bytes(64)); // 512-bit random token
    $_SESSION['csrf_token'] = $token;

    return $token;
}

function verify_csrf_token($token)
{
    return hash_equals($_SESSION['token'], $token);
}

?>