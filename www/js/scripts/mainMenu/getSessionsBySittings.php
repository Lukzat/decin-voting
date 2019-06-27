<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = json_decode(file_get_contents("php://input"));

$sessions =$APM->getSessionsBySittings($info);

echo json_encode($sessions);