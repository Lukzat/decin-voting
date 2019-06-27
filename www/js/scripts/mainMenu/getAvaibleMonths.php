<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$sittingIds = json_decode(file_get_contents("php://input"));

$sessions = $APM->getSessionsBySittings($sittingIds)[0];

$uniqueMonths = [];
foreach ($sessions as $session) {
    if(!in_array(date('m', $session["date"]), $uniqueMonths)){
        $uniqueMonths[]=(int)date('m', $session["date"]);
    }
}

echo json_encode($uniqueMonths);