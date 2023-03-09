<?php
require_once './config.php';

/* Destroying the session and redirecting the user to the login page. */
$_SESSION = null;
session_destroy();
header('Location: ' . SITE_ROOT . 'login.php');
exit;
