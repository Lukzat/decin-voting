<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$types = json_decode(file_get_contents("php://input"));

$sessionIds = $APM->getSessionIdsByType($types);

$people = $APM->getPersonsBySessions($sessionIds);

$uniqueGroups = [];
foreach ($people as $cluster) {
    foreach ($cluster as $data) {
        if(!in_array($data["groupName"], $uniqueGroups)){
            $uniqueGroups[]=$data["groupName"];
        }
    }
}

echo json_encode($uniqueGroups);