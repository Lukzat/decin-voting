<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = (array)json_decode(file_get_contents("php://input"));

$checkZasedani = True;
$checkBodProgramu = True;
$checkPolitickaStrana = True;
$checkNameAndSurname = True;


if(sizeof($info["sittingTypes"])==0){
    $checkZasedani = False;
    //echo 'sittingTypes je prázdné';
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


$sessionsCluster = $APM->getSessionsByNewSittings($info["sittingTypes"]);

//var_dump($info["bodProgramuData"]);
//var_dump($sessionsCluster);

$sessions = [];
foreach ($sessionsCluster as $sessionsBundle) {
    foreach ($sessionsBundle as $session) {
        $sessions[]=$session;
    }
}
//var_dump($sessions);

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
        }elseif (strpos($person["decision"], 'Nepřítomen') !== False) {
            $nehlasoval++;
        }
    }
    $chartValues = [$ano,$ne,$zdrzelSe,$omluven,$nehlasoval];
    $graphClusterData[]=$chartValues;
}



$complexSearchResult = [$sessions,$voteCluster,$graphClusterData];

echo json_encode($complexSearchResult);