<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = (array)json_decode(file_get_contents("php://input"));

$checkMonth = True;
$checkBodProgramu = True;
$checkPolitickaStrana = True;
$checkNameAndSurname = True;


if(sizeof($info["sittingId"])==0){
    return 'Příliš široké vyhledávání.';
    exit();
}
if(sizeof($info["mesicData"])==0){
    $checkMonth = False;
    //echo 'mesicData je prázdné';
}
if(sizeof($info["bodProgramuData"])==0){
    $checkBodProgramu = False;
    //echo 'bodProgramuData je prázdné';
}
if(sizeof($info["politickaStranaData"])==0){
    $checkPolitickaStrana = False;
    //echo 'politickaStranaData je prázdné';
}
if(sizeof($info["jmenoData"])==0){
    $checkNameAndSurname = False;
    //echo 'jmenoData je prázdné';
}


$sessions = $APM->getSessionsBySittings($info["sittingId"])[0];

//var_dump($info["bodProgramuData"]);
//var_dump(sizeof($sessions));


if($checkMonth){
    $tempSessions = [];
    foreach ($sessions as $session) {
        if(in_array(date('m', $session["date"]), $info["mesicData"])){
            $tempSessions[]=$session;
        }
    }
    $sessions = $tempSessions;
}


if($checkBodProgramu){
    $tempSessions = [];
    foreach ($sessions as $session) {
        if(in_array($session["type"], $info["bodProgramuData"])){
            $tempSessions[]=$session;
        }
    }
    $sessions = $tempSessions;
}



$sessionIds = [];
foreach ($sessions as $session) {
    $sessionIds[]=$session["id"];
}
$voteCluster = $APM->getPersonsBySessions($sessionIds);


//var_dump($info["politickaStranaData"]);
//var_dump($voteCluster);
if($checkPolitickaStrana){
    $tempVoteCluster = [];
    foreach ($voteCluster as $cluster) {
        $dataHolder = [];
        foreach ($cluster as $data) {
            if(in_array($data["groupName"], $info["politickaStranaData"])){
                $dataHolder[]=$data;
            }
        }
        $tempVoteCluster[]=$dataHolder;
    }
    $voteCluster = $tempVoteCluster;
}
//var_dump($voteCluster);

if($checkNameAndSurname){
    $tempVoteCluster = [];
    foreach ($voteCluster as $cluster) {
        $dataHolder = [];
        foreach ($cluster as $data) {
            if(in_array($data["name"], $info["jmenoData"])){
                $dataHolder[]=$data;
            }
        }
        $tempVoteCluster[]=$dataHolder;
    }
    $voteCluster = $tempVoteCluster;
}
//var_dump($sessions);
//exit();


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
        }
    }
    $chartValues = [$ano,$ne,$zdrzelSe,$omluven,$nehlasoval];
    $graphClusterData[]=$chartValues;
}



$complexSearchResult = [$sessions,$voteCluster,$graphClusterData];

echo json_encode($complexSearchResult);