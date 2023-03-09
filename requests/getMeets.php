<?php
require_once '../config.php';
require_once '../classes/dbGestion.php';

$sql = new dbGestion();
$data = $sql->getMeetsUserById($_SESSION['user']['id']);
$sql->disconnect($sql->mysqli);
echo json_encode($data);
