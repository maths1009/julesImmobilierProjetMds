<?php
require_once './config.php';

$currentPage = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['user']) && $currentPage !== 'login.php') {
    header('Location: ' . SITE_ROOT . 'login.php');
    exit;
} elseif (isset($_SESSION['user']) && $currentPage === 'login.php') {
    header('Location: ' . SITE_ROOT . 'index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jules Immobilier</title>
    <link rel="stylesheet" href="./assets/css/normalizeCSS.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
    <link rel="stylesheet" href="./assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>