<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = (array)json_decode(file_get_contents("php://input"));

$allZastupitelstva = False;
$allBodProgramu = False;
$allPolitickaStrana = False;
$allNameAndSurname = False;
if(sizeof($info["jmenoAPrijmeni"]) == 0){
    $allNameAndSurname = True;
}
if(sizeof($info["politickeStrany"]) == 0){
    $allPolitickaStrana = True;
}
if(sizeof($info["bodyProgramu"]) == 0){
    $allBodProgramu = True;
}
if(sizeof($info["zastupitelstva"]) == 0){
    $allZastupitelstva = True;
}

//Get all Names
$people = $APM->getPersonsBySessions("ALL");
$uniquePeopleNames = [];
foreach ($people as $cluster) {
    foreach ($cluster as $data) {
        if(!in_array($data["name"], $uniquePeopleNames)){
            $uniquePeopleNames[]=$data["name"];
        }
    }
}

//Get all groups
$people = $APM->getPersonsBySessions("ALL");
$uniqueGroups = [];
foreach ($people as $cluster) {
    foreach ($cluster as $data) {
        if(!in_array($data["groupName"], $uniqueGroups)){
            $uniqueGroups[]=$data["groupName"];
        }
    }
}

//Get all points
$sessions = $APM->getBasicSessionData();
$sendSessions = [];
foreach ($sessions as $session) {
    if(!in_array($session["type"], $sendSessions)){
        $sendSessions[] = $session["type"];
    }
}

//Get all sessions
$sessions2 = $APM->getBasicSessionData();
$sittings = [];
$sittingsNumHolder = [];
foreach ($sessions2 as $session) {
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


if(!$allNameAndSurname){
    $people888 = $APM->getPersonsBySessions("ALL");
    $uniquePeopleNames888 = [];
    foreach ($people888 as $cluster888) {
        foreach ($cluster888 as $data888) {
            if(!in_array($data888["name"], $uniquePeopleNames888)){
                $uniquePeopleNames888[]=$data888["name"];
            }
        }
    }
    $remGroupNames888 = [];
    $remSessinoIds888 = [];
    foreach ($info["jmenoAPrijmeni"] as $name888) {
        $votes888 = $APM->getVotesByPersonName($name888);
        foreach ($votes888 as $vote888) {
            if(!in_array($vote888["groupName"], $remGroupNames888)){
                $remGroupNames888[] = $vote888["groupName"];
            }
            if(!in_array($vote888["sessionId"], $remSessinoIds888)){
                $remSessinoIds888[] = $vote888["sessionId"];
            }
        }
    }
    //var_dump($remSessinoIds);
    //var_dump($remGroupNames);
    $uniqueGroups888 = $remGroupNames888;
    //var_dump($remSessinoIds);
    $sessions888 = $APM->getBasicSessionData();
    $sendSessions888 = [];
    $sittings888 = [];
    $sittingsNumHolder888 = [];
    foreach ($sessions888 as $session888) {
        if(in_array($session888["id"], $remSessinoIds888)){
            $sendSessions888[] = $session888["type"];
            $impoPart888 = strstr($session888["type"], 'ZM');
            $impoPart888 = substr($impoPart888, 3, 5);
            $year888 = substr($impoPart888, 0, 2);
            $month888 = substr($impoPart888, 3, 2);
            $wholeNum888 = (int)($year888 . $month888);
            if(!in_array($wholeNum888, $sittingsNumHolder888)){
                $sittingsNumHolder888[]=$wholeNum888;
                $sittings888[]=["searchName"=>"ZM " . $year888 . " " . $month888,"displayName"=>"ZM " . $month888 . "/20" . $year888];
            }
        }
            
    }
    //var_dump($sendSessions);
    //var_dump($sittings);
    foreach ($sittings as $testVal) {
        if(!in_array($testVal, $sittings888)){
            $key = array_search($testVal, $sittings);
            unset($sittings[$key]);
        }
    }
    $sittings = array_values($sittings);
    foreach ($sendSessions as $testVal) {
        if(!in_array($testVal, $sendSessions888)){
            $key = array_search($testVal, $sendSessions);
            unset($sendSessions[$key]);
        }
    }
    $sendSessions = array_values($sendSessions);
    foreach ($uniqueGroups as $testVal) {
        if(!in_array($testVal, $uniqueGroups888)){
            $key = array_search($testVal, $uniqueGroups);
            unset($uniqueGroups[$key]);
        }
    }
    $uniqueGroups = array_values($uniqueGroups);
}
if(!$allPolitickaStrana){
    $people888 = $APM->getPersonsBySessions("ALL");
    $uniqueGroups888 = [];
    foreach ($people888 as $cluster888) {
        foreach ($cluster888 as $data888) {
            if(!in_array($data888["groupName"], $uniqueGroups888)){
                $uniqueGroups888[]=$data888["groupName"];
            }
        }
    }
    $NNames888 = [];
    $remSessinoIds888 = [];
    foreach ($info["politickeStrany"] as $name888) {
        $votes888 = $APM->getVotesByPS($name888);
        foreach ($votes888 as $vote888) {
            if(!in_array($vote888["name"], $NNames888)){
                $NNames888[] = $vote888["name"];
            }
            if(!in_array($vote888["sessionId"], $remSessinoIds888)){
                $remSessinoIds888[] = $vote888["sessionId"];
            }
        }
    }
    //var_dump($NNames);
//    var_dump($remSessinoIds);
    $uniquePeopleNames888 = $NNames888;
    
    $sessions888 = $APM->getBasicSessionData();
    $sendSessions888 = [];
    $sittings888 = [];
    $sittingsNumHolder888 = [];
    foreach ($sessions888 as $session888) {
        if(in_array($session888["id"], $remSessinoIds888)){
            $sendSessions888[] = $session888["type"];
            $impoPart888 = strstr($session888["type"], 'ZM');
            $impoPart888 = substr($impoPart888, 3, 5);
            $year888 = substr($impoPart888, 0, 2);
            $month888 = substr($impoPart888, 3, 2);
            $wholeNum888 = (int)($year888 . $month888);
            if(!in_array($wholeNum888, $sittingsNumHolder888)){
                $sittingsNumHolder888[]=$wholeNum888;
                $sittings888[]=["searchName"=>"ZM " . $year888 . " " . $month888,"displayName"=>"ZM " . $month888 . "/20" . $year888];
            }
        }
            
    }
//    var_dump($sendSessions);
//    var_dump($sittings);
    foreach ($sittings as $testVal) {
        if(!in_array($testVal, $sittings888)){
            $key = array_search($testVal, $sittings);
            unset($sittings[$key]);
        }
    }
    $sittings = array_values($sittings);
    foreach ($sendSessions as $testVal) {
        if(!in_array($testVal, $sendSessions888)){
            $key = array_search($testVal, $sendSessions);
            unset($sendSessions[$key]);
        }
    }
    $sendSessions = array_values($sendSessions);
    foreach ($uniquePeopleNames as $testVal) {
        if(!in_array($testVal, $uniquePeopleNames888)){
            $key = array_search($testVal, $uniquePeopleNames);
            unset($uniquePeopleNames[$key]);
        }
    }
    $uniquePeopleNames = array_values($uniquePeopleNames);
}
if(!$allBodProgramu){
    $sessions888 = $APM->getBasicSessionData();
    $sendSessions888 = [];
    foreach ($sessions888 as $session888) {
        if(!in_array($session888["type"], $sendSessions888)){
            $sendSessions888[] = $session888["type"];
        }
    }
    $remSessinoIds888 = [];
    foreach ($info["bodyProgramu"] as $name888) {
        $votes888 = $APM->getSessionsByType($name888);
        foreach ($votes888 as $vote888) {
            if(!in_array($vote888["id"], $remSessinoIds888)){
                $remSessinoIds888[] = $vote888["id"];
            }
        }
    }
    $sessions888 = $APM->getBasicSessionData();
    $sittings888 = [];
    $sittingsNumHolder888 = [];
    foreach ($sessions888 as $session888) {
        if(in_array($session888["id"], $remSessinoIds888)){
            $impoPart888 = strstr($session888["type"], 'ZM');
            $impoPart888 = substr($impoPart888, 3, 5);
            $year888 = substr($impoPart888, 0, 2);
            $month888 = substr($impoPart888, 3, 2);
            $wholeNum888 = (int)($year888 . $month888);
            if(!in_array($wholeNum888, $sittingsNumHolder888)){
                $sittingsNumHolder888[]=$wholeNum888;
                $sittings888[]=["searchName"=>"ZM " . $year888 . " " . $month888,"displayName"=>"ZM " . $month888 . "/20" . $year888];
            }
        }   
    }
    $NNames888 = [];
    $gNames888 = [];
    foreach ($remSessinoIds888 as $sesId888) {
        $votes888 = $APM->getVotesBySessionId($sesId888);
        foreach ($votes888 as $vote888) {
            if(!in_array($vote888["name"], $NNames888)){
                $NNames888[] = $vote888["name"];
            }
            if(!in_array($vote888["groupName"], $gNames888)){
                $gNames888[] = $vote888["groupName"];
            }
        }
    }
    $uniqueGroups888 = $gNames888;
    $uniquePeopleNames888 = $NNames888;
    
    foreach ($sittings as $testVal) {
        if(!in_array($testVal, $sittings888)){
            $key = array_search($testVal, $sittings);
            unset($sittings[$key]);
        }
    }
    $sittings = array_values($sittings);
    foreach ($uniqueGroups as $testVal) {
        if(!in_array($testVal, $uniqueGroups888)){
            $key = array_search($testVal, $uniqueGroups);
            unset($uniqueGroups[$key]);
        }
    }
    $uniqueGroups = array_values($uniqueGroups);
    foreach ($uniquePeopleNames as $testVal) {
        if(!in_array($testVal, $uniquePeopleNames888)){
            $key = array_search($testVal, $uniquePeopleNames);
            unset($uniquePeopleNames[$key]);
        }
    }
    $uniquePeopleNames = array_values($uniquePeopleNames);
}
if(!$allZastupitelstva){
    $sessions2888 = $APM->getBasicSessionData();
    $sittings888 = [];
    $sittingsNumHolder888 = [];
    $sesIds888 = [];
    $sendSessions888 = [];
    foreach ($sessions2888 as $session888) {
        $impoPart888 = strstr($session888["type"], 'ZM');
        $impoPart888 = substr($impoPart888, 3, 5);
        $year888 = substr($impoPart888, 0, 2);
        $month888 = substr($impoPart888, 3, 2);
        $wholeNum888 = (int)($year888 . $month888);
        if(!in_array($wholeNum888, $sittingsNumHolder888)){
            $sittingsNumHolder888[]=$wholeNum888;
            $sittings888[]=["searchName"=>"ZM " . $year888 . " " . $month888,"displayName"=>"ZM " . $month888 . "/20" . $year888];
        }
        $tstPiece888 = ["searchName"=>"ZM " . $year888 . " " . $month888,"displayName"=>"ZM " . $month888 . "/20" . $year888];
        foreach ($info["zastupitelstva"] as $sName888) {
            if($tstPiece888["searchName"] == $sName888){
                $sesIds888[]=$session888["id"];
                $sendSessions888[]=$session888["type"];
            }
        }
    }
//    var_dump($sittings);
    $NNames888 = [];
    $gNames888 = [];
    foreach ($sesIds888 as $sesId888) {
        $votes888 = $APM->getVotesBySessionId($sesId888);
        foreach ($votes888 as $vote888) {
            if(!in_array($vote888["name"], $NNames888)){
                $NNames888[] = $vote888["name"];
            }
            if(!in_array($vote888["groupName"], $gNames888)){
                $gNames888[] = $vote888["groupName"];
            }
        }
    }
    $uniqueGroups888 = $gNames888;
    $uniquePeopleNames888 = $NNames888;
    
    foreach ($sendSessions as $testVal) {
        if(!in_array($testVal, $sendSessions888)){
            $key = array_search($testVal, $sendSessions);
            unset($sendSessions[$key]);
        }
    }
    $sendSessions = array_values($sendSessions);
    foreach ($uniqueGroups as $testVal) {
        if(!in_array($testVal, $uniqueGroups888)){
            $key = array_search($testVal, $uniqueGroups);
            unset($uniqueGroups[$key]);
        }
    }
    $uniqueGroups = array_values($uniqueGroups);
    foreach ($uniquePeopleNames as $testVal) {
        if(!in_array($testVal, $uniquePeopleNames888)){
            $key = array_search($testVal, $uniquePeopleNames);
            unset($uniquePeopleNames[$key]);
        }
    }
    $uniquePeopleNames = array_values($uniquePeopleNames);
}


sort($uniqueGroups);
sort($uniquePeopleNames);
$complexSearchResult = [$sittings,$sendSessions,$uniqueGroups,$uniquePeopleNames,
    $info["jmenoAPrijmeni"],$info["politickeStrany"],$info["bodyProgramu"],$info["zastupitelstva"]];

echo json_encode($complexSearchResult);