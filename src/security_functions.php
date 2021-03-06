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

function csrf_token_is_valid($token)
{
    return !empty($token) && hash_equals($_SESSION['csrf_token'], $token);
}

?>