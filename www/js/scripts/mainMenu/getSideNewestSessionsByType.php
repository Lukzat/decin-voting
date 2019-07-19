<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$searchName = json_decode(file_get_contents("php://input"));

$sessionsCluster = $APM->getSessionsByNewSitting($searchName->searchName);

foreach ($sessionsCluster as $session) {
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
    $neededKey3 = array_keys($sessionsCluster, $session);
    $sessionsCluster[$neededKey3[0]]["description"] = (string)$textBit . "...";
}

echo json_encode($sessionsCluster);