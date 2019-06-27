<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = json_decode(file_get_contents("php://input"));

$persons =$APM->getPersonsBySessions($info);



$chartCluster = [];

foreach ($persons as $cluster) {
    $ano=0;
    $ne=0;
    $zdrzelSe=0;
    $omluven=0;
    $nehlasoval=0;
    foreach ($cluster as $person) {
        if(strpos($person["decision"], "Pro") !== False){
            $ano++;
        }elseif (strpos($person["decision"], 'Proti') !== False) {
            $ne++;
        }elseif (strpos($person["decision"], 'Zdržel se') !== False) {
            $zdrzelSe++;
        }elseif (strpos($person["decision"], 'Omluven') !== False) {
            $omluven++;
        }elseif (strpos($person["decision"], 'Nehlasoval') !== False) {
            $nehlasoval++;
        }
    }
    $chartValues = [$ano,$ne,$zdrzelSe,$omluven,$nehlasoval];
    $chartCluster[]=$chartValues;
}

$sendFArray = [$persons,$chartCluster];

//$showPersons = [];
//$personsData = $persons;
//$finalPersonCluster = [];
//foreach ($persons as $person) {
//    if(!in_array($person["name"], $showPersons)){
//        $showPersons[] = $person["name"];
//    }
//}
//$finalPersonCluster[0] = $showPersons;
//$finalPersonCluster[1] = $personsData;

echo json_encode($sendFArray);