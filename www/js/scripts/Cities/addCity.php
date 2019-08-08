<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();

$cityName = file_get_contents("php://input");

$result = $APM->addCity($cityName);

echo json_encode($result);