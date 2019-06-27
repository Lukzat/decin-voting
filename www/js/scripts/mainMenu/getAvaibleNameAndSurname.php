<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = (array)json_decode(file_get_contents("php://input"));

$types = $info["session"];

$pGroups = $info["pGroups"];

$sessionIds = $APM->getSessionIdsByType($types);

$people = $APM->getPersonsBySessions($sessionIds);

$remainingPersons = [];
foreach ($people as $cluster) {
    $dataHolder = [];
    foreach ($cluster as $data) {
        if(in_array($data["groupName"], $pGroups)){
            $dataHolder[]=$data;
        }
    }
    $remainingPersons[]=$dataHolder;
}

$uniquePeopleNames = [];
foreach ($remainingPersons as $cluster) {
    foreach ($cluster as $data) {
        if(!in_array($data["name"], $uniquePeopleNames)){
            $uniquePeopleNames[]=$data["name"];
        }
    }
}

echo json_encode($uniquePeopleNames);