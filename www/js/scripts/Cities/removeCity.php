<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();

$cityId = file_get_contents("php://input");

$result = $APM->removeCity($cityId);

echo json_encode($result);