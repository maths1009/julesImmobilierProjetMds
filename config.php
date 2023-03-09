<?php
/* Starting a session. */
session_start();

/* Setting the locale to French. */
setlocale(LC_TIME, 'fr_FR');

/* Defining the constant SITE_ROOT to the directory of the current file. */
define('SITE_ROOT', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])) . '/');

/* Defining the database host, user and password. */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'julesimmo');
