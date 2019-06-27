<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$id = json_decode(file_get_contents("php://input"));

$session =$APM->getsessionById($id);

echo json_encode($session);