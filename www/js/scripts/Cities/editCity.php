<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();

$info = json_decode(file_get_contents("php://input"),true);

$result = $APM->editCity($info);

echo json_encode($result);