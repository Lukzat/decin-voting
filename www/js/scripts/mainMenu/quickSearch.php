<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = json_decode(file_get_contents("php://input"),true);
$searchWord = (string)$info['word'];

$results = $APM->quickSearch($info);
if(!$results){
    $results = [[],[]];
}
if(!isset($results[0])){
    $results[0] = [];
}
//var_dump($results);

$uniqueNames = [];
foreach ($results[1] as $person) {
    if(!in_array($person["name"], $uniqueNames)){
        $uniqueNames[]=$person["name"];
    }
}

$defNum = 100;
$pickSes = 0;
$pickVot = 0;

if(sizeof($results[0])>50){
    $pickSes = 50;
}else{
    $pickSes = sizeof($results[0]);
}

if(sizeof($uniqueNames)>50){
    $pickVot = 50;
}else{
    $pickVot = sizeof($uniqueNames);
}

$pickedSes = [];
for ($i = 0;$i < $pickSes; $i++) {
    $pickedSes[] = $results[0][$i];
}

$pickedVot = [];
for ($i = 0;$i < $pickVot; $i++) {
    $pickedVot[] = $uniqueNames[$i];
}

$count = 0;
foreach ($pickedSes as $ses) {
    $pickedSes[$count]["description"] = preg_replace("/" . $searchWord . "/i", '"<b>$0</b>"', $ses["description"]);
    $count++;
}

$count = 0;
foreach ($pickedSes as $ses) {
    $pickedSes[$count]["type"] = preg_replace("/" . $searchWord . "/i", '<b>$0</b>', $ses["type"]);
    $count++;
}

//var_dump($pickedSes);
//var_dump($pickVot);
//exit();

$finalSend = [$pickedSes,$pickedVot];

echo json_encode($finalSend);