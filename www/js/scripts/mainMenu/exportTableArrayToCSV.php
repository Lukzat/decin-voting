<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = json_decode(file_get_contents("php://input"));

$randomBit = (string)rand();

$fp = fopen("../../../CSVHolder/CSV-".$randomBit.".csv",'w') or die("Can't open CSVHolder/CSV-".$randomBit.".csv");

for ($i = 0; $i < sizeof($info); $i++) {
    $ses = $info[$i]->ses;
    $per = $info[$i]->per;
    $char = $info[$i]->char;
    //var_dump($ses);
    
    $impoPart = strstr($ses->type, 'ZM');
    $impoPart = substr($impoPart, 3, 5);
    $year = substr($impoPart, 0, 2);
    $month = substr($impoPart, 3, 2);
    $wholeNum = (int)($year . $month);
    $zastup = "ZM " . $month . "/20" . $year;
    
    $valuesSes = ["Zastupitelstvo: ".(string)$zastup,"Bod programu: ".(string)$ses->type,"Datum: ".(string)date('d.m.Y', $ses->date)];
    fputcsv($fp, $valuesSes, ';');
    
    fputcsv($fp, [], ';');
    foreach ($per as $person) {
        //var_dump($person);
        $valuesPer = ["Jméno: ".(string)$person->name,"Politická strana: ".(string)$person->groupName,"Rozhodnutí: ".(string)$person->decision];
        fputcsv($fp, $valuesPer, ';');
    }
    fputcsv($fp, [], ';');
    $valuesChar = ["Pro: ".(string)$char[0],
                "Proti: ".(string)$char[1],
                "Omluven: ".(string)$char[2],
                "Nehlasoval: ".(string)$char[3]];
    
    fputcsv($fp, $valuesChar, ';');
    fputcsv($fp, [], ';');
    fputcsv($fp, [], ';');
    fputcsv($fp, [], ';');
}

fclose($fp) or die("Can't close CSVHolder/CSV-".$randomBit.".csv");

$csvFileName = "CSV-" . $randomBit.".csv";
echo json_encode($csvFileName);
exit();

$persons =$APM->getPersonsBySessions($info);



$chartCluster = [];

foreach ($persons as $cluster) {
    $ano=0;
    $ne=0;
    $zdrzelSe=0;
    $omluven=0;
    $nehlasoval=0;
    foreach ($cluster as $person) {
        if(strpos($person["decision"], "Pro") !== False){
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
    $chartCluster[]=$chartValues;
}

$sendFArray = [$persons,$chartCluster];

//$showPersons = [];
//$personsData = $persons;
//$finalPersonCluster = [];
//foreach ($persons as $person) {
//    if(!in_array($person["name"], $showPersons)){
//        $showPersons[] = $person["name"];
//    }
//}
//$finalPersonCluster[0] = $showPersons;
//$finalPersonCluster[1] = $personsData;

echo json_encode($sendFArray);