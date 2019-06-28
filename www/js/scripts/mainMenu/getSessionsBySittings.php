<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = json_decode(file_get_contents("php://input"));

$sessionsCluster = $APM->getSessionsByNewSittings($info);

$bundledSessions = [];
foreach ($sessionsCluster as $sessions) {
    foreach ($sessions as $session) {
        $bundledSessions[]=$session;
    }
}

echo json_encode($bundledSessions);