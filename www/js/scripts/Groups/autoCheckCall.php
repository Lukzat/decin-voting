<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use Manager\AdministraceManager;
use Tracy\Debugger;
use Utilities\Functions;

header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();
$F = new Functions();

$log_date = new DateTime();

$log_file_name = 'log_' . $log_date->format('d-m-Y');
$url = 'https://opendata.mmdecin.cz/api/3/action/package_search?q=hlasovani-zastupitelstva';
$opts = array(
    "ssl" => array(
        "verify_peer" => false,
        "verify_peer_name" => false,
    ),
);

$APM->truncateTables();
$ch = curl_init(); // převést do samostátné třídy
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);

if (!$obj->success) {
    Debugger::log('Nepodařilo se navázat spojení s APi.', $log_file_name);
    echo json_encode('Nepodařilo se navázat spojení s APi.');
    exit();
}

foreach ($obj->result->results[0]->resources as $objectOfInterest) {
    $newFileUrl = $objectOfInterest->url; // adressa souboru
    if ($objectOfInterest->format == "CSV") {
        $handle = fopen($newFileUrl, "r", false, stream_context_create($opts));
        if ($handle !== false) {
            $sittingId = 30; // constanta? jaky ma vyznam
            $uniqueSessionsNames = [];
            $uniqueSessions = [];
            $votesClusters = [];
            $row = 1;
            while (($infoClusters = fgetcsv($handle, 100000, ";")) !== false) {
                if (($fail = $F->is_valid_csv($infoClusters)) !== true) {
                    echo $newFileUrl . ' ' . $fail;
                    Debugger::log($newFileUrl . ' ' . $fail, $log_file_name);
                    break;
                }
                if (!in_array($infoClusters[7], $uniqueSessionsNames)) {
                    if (isset($votesCluster)) {
                        $votesClusters[] = $votesCluster;
                    }
                    $votesCluster = [];
                    $votesCluster[] = [
                        "name" => $infoClusters[1] . " " . $infoClusters[2],
                        "groupName" => $infoClusters[3],
                        "decision" => $infoClusters[9]];
                    $uniqueSessionsNames[] = $infoClusters[7];
                    $stringTime = $infoClusters[5] . " " . $infoClusters[6];
                    $dtime = DateTime::createFromFormat("d.m.Y H:i:s", $stringTime);
                    if ($dtime !== false)
                        $timestamp = $dtime->getTimestamp();
                    else {
                        $timestamp = -1;
                        Debugger::log($newFileUrl . ' špatná hodnota datumu na řádku: ' . $row, $log_file_name);
                    }
                    $uniqueSessions[] = [
                        AdministraceManager::COLUMN_EVENT => "",
                        AdministraceManager::COLUMN_CHAIRMAN => "",
                        AdministraceManager::COLUMN_STATE => "",
                        AdministraceManager::COLUMN_DATE => $timestamp,
                        AdministraceManager::COLUMN_TYPE => $infoClusters[7],
                        AdministraceManager::COLUMN_DESCRIPTION => $infoClusters[8],
                        AdministraceManager::COLUMN_ABOVE_DESCRIPTION => "",
                        AdministraceManager::COLUMN_SITTINGS_ID => $sittingId];
                } else {
                    $votesCluster[] = ["name" => $infoClusters[1] . " " . $infoClusters[2], "groupName" => $infoClusters[3], "decision" => $infoClusters[9]];
                }
                $row++;
            }
            fclose($handle);
            foreach ($uniqueSessions as $i => $value) {
                $APM->addVotingSession2($value);
                $sessionId = $APM->getVotingSessionByEventChairmanState($value["date"], $sittingId);
                if (isset($votesClusters[$i])) {
                    foreach ($votesClusters[$i] as &$person) {
                        $person[AdministraceManager::COLUMN_SESSION_ID_2] = $sessionId;
                    }
                    $APM->addVotedDecision2($votesClusters[$i]);
                }
            }
            $APM->increaseNumFilesUploaded();
            Debugger::log($newFileUrl . ' proces úspěšně dokončen', $log_file_name);
        } else
            Debugger::log($newFileUrl . ' nelze otevřít soubor', $log_file_name);
    } else
        Debugger::log($newFileUrl . ' není CSV', $log_file_name);
}
