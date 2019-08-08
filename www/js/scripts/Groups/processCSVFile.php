<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();

$info = json_decode(file_get_contents("php://input"));
$sittingId = (int)$_POST["sittingId"];
$votesFile = realpath ($_FILES["hlas"]["tmp_name"]);

$infoClusters = [];

$row = 1;
if (($handle = fopen($votesFile, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 100000, ";")) !== FALSE) {
	$infoCluster = [];
    $num = count($data);
    //echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    for ($c=0; $c < $num; $c++) {
        //echo $data[$c] . "<br />\n";
		$infoCluster[]=$data[$c];
    }
	$infoClusters[]=$infoCluster;
  }
  fclose($handle);
}

//var_dump($infoClusters);
//echo '<pre>' . var_export($infoClusters, true) . '</pre>';

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

//var_dump($uniqueSessions);
//var_dump($votesClusters);

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
    
    foreach ($votesClusters[$i] as $person) {
        $APM->addVotedDecision($person["name"],$person["groupName"],$person["decision"],$sessionId);
    }
}


echo json_encode("Proces dokončen");
exit();