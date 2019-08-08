<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();

$result = $APM->getCities();

echo json_encode($result);