<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = json_decode(file_get_contents("php://input"));

$votes = array_map('iterator_to_array',$APM->getVotesBySessions($info));


$names = [];
for($i=0;$i<sizeof($votes);$i++)
{
    for($a=0;$a<sizeof($votes[$i]);$a++)
    {
        if(!(in_array($votes[$i][$a]->name, $names)))
        {
            array_push($names, $votes[$i][$a]->name);
        }
    }
}

echo json_encode($names);