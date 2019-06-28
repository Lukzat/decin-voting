<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$sessions = $APM->getBasicSessionData();

$sittings = [];
$sittingsNumHolder = [];
foreach ($sessions as $session) {
    $impoPart = strstr($session["type"], 'ZM');
    $impoPart = substr($impoPart, 3, 5);
    $year = substr($impoPart, 0, 2);
    $month = substr($impoPart, 3, 2);
    $wholeNum = (int)($year . $month);
    if(!in_array($wholeNum, $sittingsNumHolder)){
        $sittingsNumHolder[]=$wholeNum;
        $sittings[]=["searchName"=>"ZM " . $year . " " . $month,"displayName"=>"ZM " . $month . "/20" . $year];
    }
}

$neededKey1 = array_keys($sittingsNumHolder, max($sittingsNumHolder));


foreach ($sessions as $session) {
    if (strpos($session["type"], $sittings[$neededKey1[0]]["searchName"]) !== false) {
        
    }else{
        $neededKey2 = array_keys($sessions, $session);
        unset($sessions[$neededKey2[0]]);
    }
}


foreach ($sessions as $session) {
    if(((int)strlen($session["description"]))<90){
        $distantPosition = (int)strlen($session["description"]);
    }else{
        $distantPosition = 90;
    }
    $textBit = substr(str_replace('"', '\"', $session["description"]), 0, $distantPosition);
    $regex = <<<'END'
    /
      (
        (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
        |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
        |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
        |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
        ){1,100}                        # ...one or more times
      )
    | .                                 # anything else
    /x
    END;
    $textBit = preg_replace($regex, '$1', $textBit);
    $neededKey3 = array_keys($sessions, $session);
    $sessions[$neededKey3[0]]["description"] = (string)$textBit . "...";
}

$sittingsLabels = [];
$sittingsNames = [];
foreach ($sittings as $sittingInfo) {
    $sittingsLabels[] = $sittingInfo["displayName"];
    $sittingsNames[] = $sittingInfo["searchName"];
}

$sessionsCluster = $APM->getSessionsByNewSittings($sittingsNames);
$sessionsIds = [];
foreach ($sessionsCluster as $sessionsBundle) {
    $idCluster = [];
    foreach ($sessionsBundle as $session) {
        $idCluster[]=$session["id"];
    }
    $sessionsIds[]=$idCluster;
}

$hlasovaloResults = [];
$nehlasovaloResults = [];
foreach ($sessionsIds as $cluster) {
    $results = $APM->countVotedAndNotForOneSitting($cluster);
    $hlasovaloResults[] = $results[0];
    $nehlasovaloResults[] = $results[1];
}

$sendArray = [$sessions,$sittings,sizeof($sittings),$sittingsLabels,$hlasovaloResults,$nehlasovaloResults];

echo json_encode($sendArray);
