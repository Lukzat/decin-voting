<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = (array)json_decode(file_get_contents("php://input"));

$userName = (string)$info["name"];

//var_dump($userName);

$personalVotedCluster = $APM->getpersonalVotedCluster($userName);

$sessionIds = [];
foreach ($personalVotedCluster as $voteRecord) {
    $sessionIds[] = $voteRecord["sessionId"];
}


$sessions = $APM->getSessionsByIds($sessionIds);

//var_dump($personalVotedCluster);
//exit();

$count = 0;
foreach ($sessions as $session) {
    $textBit = substr($session[0]["description"], 0, 30);
    $sessions[$count][0]["description"] = (string)$textBit . "...";
    $count++;
}


$ano=0;
$ne=0;
$zdrzelSe=0;
$omluven=0;
$nehlasoval=0;
foreach ($personalVotedCluster as $vote) {
    if((strpos($vote["decision"], "Pro") !== False)&&(strpos($vote["decision"], 'Proti') === False) ){
        $ano++;
    }elseif (strpos($vote["decision"], 'Proti') !== False) {
        $ne++;
    }elseif (strpos($vote["decision"], 'Zdržel se') !== False) {
        $zdrzelSe++;
    }elseif (strpos($vote["decision"], 'Omluven') !== False) {
        $omluven++;
    }elseif (strpos($vote["decision"], 'Nehlasoval') !== False) {
        $nehlasoval++;
    }
}
$chartValues = [$ano,$ne,$zdrzelSe,$omluven,$nehlasoval];

$complexSearchResult = [$sessions,$personalVotedCluster,$chartValues];

echo json_encode($complexSearchResult);