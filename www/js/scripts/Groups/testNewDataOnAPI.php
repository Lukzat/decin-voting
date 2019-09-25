<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();

$filesUploaded = $APM->getNumFilesUploaded();
//$filesUploaded = 5;
$url = 'https://opendata.mmdecin.cz/api/3/action/package_search?q=hlasovani-zastupitelstva';


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
    }
    if(!($filesUploaded >= $count)){
        echo json_encode($objectOfInterest->url);
        exit();
    }
}

echo json_encode("Up to date");