<?php
require_once '../config.php';
require_once '../classes/dbGestion.php';

$sql = new dbGestion();
$data = $_SESSION['user']['role_id'] == 1 || $_SESSION['user']['id'] == 2 ? $sql->selectAll('meets') :  $sql->getMeetsUserById($_SESSION['user']['id']);
$sql->disconnect($sql->mysqli);
echo json_encode($data);
