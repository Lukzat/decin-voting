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
    $sessionIds[]=$voteRecord["sessionId"];
}


$sessions = $APM->getSessionsByIds($sessionIds);

//var_dump($personalVotedCluster);
//exit();

foreach ($sessions as $session) {
    if(((int)strlen($session[0]["description"]))<30){
        $distantPosition = (int)strlen($session[0]["description"]);
    }else{
        $distantPosition = 30;
    }
    
    $textBit = substr(str_replace('"', '\"', $session[0]["description"]), 0, $distantPosition);
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
    $sessions[$neededKey3[0]][0]["description"] = (string)$textBit . "...";
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
    }elseif (strpos($vote["decision"], 'Nepřítomen') !== False) {
        $nehlasoval++;
    }
}
$chartValues = [$ano,$ne,$zdrzelSe,$omluven,$nehlasoval];

$complexSearchResult = [$sessions,$personalVotedCluster,$chartValues];

echo json_encode($complexSearchResult);