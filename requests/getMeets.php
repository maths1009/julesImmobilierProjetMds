<?php
require_once '../config.php';

$mysqli = new mysqli("localhost", "root", "", "julesimmo");

if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}

$stmt = $mysqli->prepare('SELECT * FROM meets WHERE user_id = ?');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode($data);
