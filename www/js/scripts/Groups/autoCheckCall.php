<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();

$APM->truncateTables();

$filesUploaded = $APM->getNumFilesUploaded();
$filesUploaded = 0;
//$filesUploaded = 5;
$url = 'https://opendata.mmdecin.cz/api/3/action/package_search?q=hlasovani-zastupitelstva';

$newFileUrl = "none";

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
if(!$obj->success){
    echo json_encode('Nepodařilo se navázat spojení s databází.');
    exit();
}

$count = 0;
foreach ($obj->result->results[0]->resources as $objectOfInterest) {
    if($objectOfInterest->format == "CSV"){
        $count++;
        if(($filesUploaded <= $count) && ($count != 3)){
            var_dump($objectOfInterest->url);
            $newFileUrl = $objectOfInterest->url;
            $sittingId = 30;
            $votesFile = $newFileUrl;
            $infoClusters = [];
            $row = 1;
            if (($handle = fopen($votesFile, "r")) !== FALSE) {
              while (($data = fgetcsv($handle, 100000, ";")) !== FALSE) {
                    $infoCluster = [];
                $num = count($data);
                $row++;
                for ($c=0; $c < $num; $c++) {
                            $infoCluster[]=$data[$c];
                }
                    $infoClusters[]=$infoCluster;
              }
              fclose($handle);
            }
            $uniqueSessionsNames = [];
            $uniqueSessions=[];
            $votesClusters = [];
            //Filter out unique voting_sessions
            foreach ($infoClusters as $cluster) {
                $uniqueSession=[];
                if(!in_array($cluster[7], $uniqueSessionsNames)){
                    if (isset($votesCluster)) {
                        $votesClusters[]=$votesCluster;
                    }
                    $votesCluster=[];
                    $persNameCompiled = $cluster[1] . " " . $cluster[2];
                    $votesCluster[]=["name"=>$persNameCompiled,"groupName"=>$cluster[3],"decision"=>$cluster[9]];
                    $uniqueSessionsNames[]=$cluster[7];
                    $stringTime = $cluster[5] . " " . $cluster[6];
                    $dtime = DateTime::createFromFormat("d.m.Y H:i:s", $stringTime);
                    $timestamp = $dtime->getTimestamp();
                    $uniqueSession = ["event"=>"","chairman"=>"","state"=>"",
                        "date"=>$timestamp,"type"=>$cluster[7],"description"=>$cluster[8],
                        "above_description"=>"","sittings_id"=>$sittingId];
                    $uniqueSessions[]=$uniqueSession;
                }else{
                    $persNameCompiled = $cluster[1] . " " . $cluster[2];
                    $votesCluster[]=["name"=>$persNameCompiled,"groupName"=>$cluster[3],"decision"=>$cluster[9]];
                }
            }
            for($i=0;$i<sizeof ($uniqueSessions);$i++){
                $sessionSendData = [$uniqueSessions[$i]["event"],
                                    $uniqueSessions[$i]["chairman"],
                                    $uniqueSessions[$i]["state"],
                                    $uniqueSessions[$i]["date"],
                                    $uniqueSessions[$i]["type"],
                                    $uniqueSessions[$i]["description"],
                                    $uniqueSessions[$i]["above_description"],
                                    $uniqueSessions[$i]["sittings_id"]];
                $APM->addVotingSession($sessionSendData);

                $sessionId = $APM->getVotingSessionByEventChairmanState($uniqueSessions[$i]["date"],$sittingId);
                
                $temp = [];
//                var_dump($votesClusters[$i]);
                if($votesClusters[$i] !== null){
                    foreach ($votesClusters[$i] as $person) {
                        $temp[] = [
                            AdministraceManager::COLUMN_NAME_2 => $person["name"],
                            AdministraceManager::COLUMN_GROUP_NAME_2 => $person["groupName"],
                            AdministraceManager::COLUMN_DECISION_2 => $person["decision"],
                            AdministraceManager::COLUMN_SESSION_ID_2 => $sessionId
                        ];
    //                    $APM->addVotedDecision($person["name"],$person["groupName"],$person["decision"],$sessionId);
                    }
                    $APM->addVotedDecision2($temp);
                }
            }
            $APM->increaseNumFilesUploaded();
            $filesUploaded+=1;
            var_dump("Proces souboru dokončen");
        }
        if ($count == 3) {
            $APM->increaseNumFilesUploaded();
            $filesUploaded+=1;
            $count+=1;
        }
    }
}

if($newFileUrl == "none"){
    echo json_encode("Up to date");
}
