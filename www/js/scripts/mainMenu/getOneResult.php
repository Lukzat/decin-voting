<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = (array)json_decode(file_get_contents("php://input"));

$sesIdRecieved = (string)$info["sessionId"];
$sessionId = [];
$sessionId[] = $sesIdRecieved;

//var_dump($sessionId);
$sessions = $APM->getSessionsByIds($sessionId)[0];

//var_dump($info["bodProgramuData"]);
//var_dump(sizeof($sessions));

//var_dump($sessions);

$sessionIds = [];
foreach ($sessions as $session) {
    $sessionIds[]=$session["id"];
}
$voteCluster = $APM->getPersonsBySessions($sessionIds);

//var_dump($voteCluster);
//var_dump($sessions);
//exit();
//if($checkNameAndSurname){
//    $tempVoteCluster = [];
//    foreach ($voteCluster as $cluster) {
//        if(in_array($cluster["groupName"], $info["politickaStranaData"])){
//            $tempVoteCluster[]=$cluster;
//        }
//    }
//    $voteCluster = $tempVoteCluster;
//}


$graphClusterData = [];
foreach ($voteCluster as $cluster) {
    $ano=0;
    $ne=0;
    $zdrzelSe=0;
    $omluven=0;
    $nehlasoval=0;
    foreach ($cluster as $person) {
        if((strpos($person["decision"], "Pro") !== False)&&(strpos($person["decision"], 'Proti') === False) ){
            $ano++;
        }elseif (strpos($person["decision"], 'Proti') !== False) {
            $ne++;
        }elseif (strpos($person["decision"], 'Zdržel se') !== False) {
            $zdrzelSe++;
        }elseif (strpos($person["decision"], 'Omluven') !== False) {
            $omluven++;
        }elseif (strpos($person["decision"], 'Nehlasoval') !== False) {
            $nehlasoval++;
        }elseif (strpos($person["decision"], 'Nepřítomen') !== False) {
            $nehlasoval++;
        }
    }
    $chartValues = [$ano,$ne,$zdrzelSe,$omluven,$nehlasoval];
    $graphClusterData[]=$chartValues;
}



$complexSearchResult = [$sessions,$voteCluster,$graphClusterData];

echo json_encode($complexSearchResult);