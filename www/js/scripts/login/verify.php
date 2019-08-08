<?php

header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';
require_once(__DIR__ .'/../config.php');

$info = json_decode(file_get_contents("php://input"),true);

if($info["name"]==$GLOB_CONFIG["username"] && $info["pass"]==$GLOB_CONFIG["password"]){
    $result = "Match";
}else{
    $result = "Mismatch";
}

echo json_encode($result);


