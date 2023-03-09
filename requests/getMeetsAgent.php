<?php
require_once '../config.php';
require_once '../classes/dbGestion.php';

$sql = new dbGestion();
$agents = $sql->getAgentsById($_SESSION['user']['id']);
$data = [];
foreach ($agents as $agent) {
    $data[] = $sql->getMeetsById($agent['id']);
}
$sql->disconnect($sql->mysqli);
echo json_encode($data);
