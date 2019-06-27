<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = json_decode(file_get_contents("php://input"));

$names = $info->names;
$sessions = $info->sessions;

$final = [];

for($i=0;$i<sizeof($names);$i++)
{
    for($a=0;$a<sizeof($sessions);$a++)
    {
        $cluster = array_map('iterator_to_array',$APM->getVotesByPersonNames($names[$i],$sessions[$a])->fetchAll());
        for($e=0;$e<sizeof($cluster);$e++)
        {
            array_push($final, $cluster[$e]);
        }
    }
}

echo json_encode($final);