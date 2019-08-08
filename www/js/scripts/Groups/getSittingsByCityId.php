<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';


$info = json_decode(file_get_contents("php://input"),true);

$APM = new AdministraceManager();

$result = $APM->getSittingsByCityId($info);

echo json_encode($result);