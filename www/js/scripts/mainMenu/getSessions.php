<?php

use Manager\AddPageManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AddPageManager();

$sessions = array_map('iterator_to_array',$APM->getSessions()->fetchAll());

echo json_encode($sessions);