<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';


$info = file_get_contents("php://input");

$APM = new AdministraceManager();

$result = $APM->getVotesBySessionId($info);

echo json_encode($result);