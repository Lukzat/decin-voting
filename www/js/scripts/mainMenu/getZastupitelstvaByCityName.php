<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();
die;

$cityName = file_get_contents("php://input");

$sessions = $APM->getZastupitelstvaByCityName($cityName);

echo json_encode($sessions);
