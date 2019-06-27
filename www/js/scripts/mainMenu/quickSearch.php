<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$info = json_decode(file_get_contents("php://input"),true);

$results = $APM->quickSearch($info);

echo json_encode($results);
