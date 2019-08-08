<?php

use Manager\AdministraceManager;
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$APM = new AdministraceManager();

$info = json_decode(file_get_contents("php://input"));
$sittingId = (int)$_POST["sittingId"];
$votesFile = realpath ($_FILES["hlas"]["tmp_name"]);
$votesFileOpened = iconv('windows-1250', 'utf-8', file_get_contents($votesFile));
$votesHolder = $votesFileOpened;


$clusters = [];
$cluster = [];
$text = explode("\n",$votesHolder);
$foundBegining = FALSE;
//var_dump($text);
//exit();
for($a=0;$a<sizeof($text);$a++)
{
//    if(preg_match('/^([0-9]+)\.[A-Za-z]/', ltrim($text[$a], " "))||preg_match('/^([0-9]+)\.\s/', ltrim($text[$a], " "))){
//        var_dump($text[$a]);
//    }
//    var_dump($text[$a]);
    if(strlen($text[$a]) !== 0 && strlen($text[$a]) !== 1)
    {
//        var_dump($text[$a]);
        if((strpos($text[$a], 'zasedán') == false && strpos($text[$a], 'zasedan') == false
                && strpos($text[$a], 'Zasedán') == false && strpos($text[$a], 'Zasedan') == false
                && strpos($text[$a], 'jednan') == false && strpos($text[$a], 'jednán') == false
                && strpos($text[$a], 'Jednan') == false && strpos($text[$a], 'Jednán') == false
                && $foundBegining == FALSE))
        {
            echo "Začátek nenalezen, pokračuji v hledání na dalším řádku.\r\n";
            echo "\r\n";
        }else{
            if((preg_match('/[A-Za-z]/', $text[$a]) || preg_match('/[0-9]/', $text[$a])) &&
                (preg_match('/[A-Za-z]/', $text[$a+1]) || preg_match('/[0-9]/', $text[$a+1])) &&
                (preg_match('/[A-Za-z]/', $text[$a+2]) || preg_match('/[0-9]/', $text[$a+2])) &&
                (preg_match('/[A-Za-z]/', $text[$a+3]) || preg_match('/[0-9]/', $text[$a+3]))){
                
                if($foundBegining == FALSE){
                    echo "Začátek nalezen, začínám zpracovávat.\r\n";
                    echo "\r\n";
                }
                $foundBegining = TRUE;
            }
        }
        if ($foundBegining) {
            if((strpos($text[$a], 'zastupitels') !== false || strpos($text[$a], 'Zastupitels') !== false) &&
                    (strpos($text[$a], 'měst') !== false || strpos($text[$a], 'Měst') !== false || strpos($text[$a], 'mest') !== false || strpos($text[$a], 'Mest') !== false) &&
                    (strpos($text[$a+1], 'Předsedá') !== false || strpos($text[$a+1], 'Predseda') !== false || strpos($text[$a+1], 'předsedá') !== false || strpos($text[$a+1], 'predseda') !== false) &&
                    strpos($text[$a], '.') !== false &&
                    (strpos($text[$a], 'program') === false && strpos($text[$a], 'Program') === false) &&
                    (strpos($text[$a], 'odmenu') === false && strpos($text[$a], 'odměnu') === false) &&
                    (strpos($text[$a], 'navrh') === false && strpos($text[$a], 'návrh') === false) &&
                    strpos($text[$a], 'projedn') === false &&
                    strpos($text[$a], 'usnesen') === false)
            {
                array_push($clusters, $cluster);
                $cluster = [];
                array_push($cluster, $text[$a]);
            }else
            {
                if (strpos($text[$a], '______') !== false && strpos($text[$a+2], '______') !== false) {
                    array_push($clusters, $cluster);
                    $cluster = [];
                    $a+=2;
                }else if(strpos($text[$a], '______') !== false && strpos($text[$a+1], '______') !== false){
                    array_push($clusters, $cluster);
                    $cluster = [];
                    $a+=1;
                }else if(strpos($text[$a], '______') !== false && strpos($text[$a+6], '______') !== false){
                    array_push($clusters, $cluster);
                    $cluster = [];
                    $a+=6;
                }else if(preg_match('/([0-9.]+). zasedanm/i', $text[$a])){
                    array_push($clusters, $cluster);
                    $cluster = [];
                    array_push($cluster, $text[$a]);
                }
                else if(preg_match('/([0-9.]+). jednanm/i', $text[$a])){
                    array_push($clusters, $cluster);
                    $cluster = [];
                    array_push($cluster, $text[$a]);
                }else{
                    array_push($cluster, $text[$a]);
                }
            }
        }
    }
}
array_push($clusters, $cluster);

//var_dump($clusters);
for($i=0;$i<sizeof($clusters);$i++)
{
    if(sizeof($clusters[$i])>=8)
    {
        $event = "empty";
        $chairman = "empty";
        $state = "empty";
        $dateString = "empty";
        $type = "empty";
        
        $voteAboveDescription = [];
        $votedDecision = [];
        $voteDescription = [];
        $numOfDeviders = 0;
        for($a=0;$a<sizeof($clusters[$i]);$a++)
        {
            if(strpos($clusters[$i][$a], '______') !== false)
            {
                $numOfDeviders++;
            }else{
                if ($numOfDeviders==1) {
                    if(preg_match('/^Stránka ([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^stránka ([0-9.]+)/', $clusters[$i][$a])
                            || preg_match('/^Stranka ([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^stranka ([0-9.]+)/', $clusters[$i][$a])
                            || preg_match('/^Hlas([0-9.]+).([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^hlas([0-9.]+).([0-9.]+)/', $clusters[$i][$a])
                            || preg_match('/Hlas ([0-9.]+).([0-9.]+)/', $clusters[$i][$a]) || preg_match('/hlas ([0-9.]+).([0-9.]+)/', $clusters[$i][$a])
                            || strlen($clusters[$i][$a])<8){

                        echo "Přeskakuji zbytečný řádek.";
                        echo "\r\n";
                    }else{
                        $votedDecision[] = $clusters[$i][$a];
                    }
                }else if($numOfDeviders==0){
                    if(strlen($clusters[$i][$a])>=3){
                        if(preg_match('/^Stránka ([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^stránka ([0-9.]+)/', $clusters[$i][$a])
                                || preg_match('/^Stranka ([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^stranka ([0-9.]+)/', $clusters[$i][$a])
                                || preg_match('/^Hlas([0-9.]+).([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^hlas([0-9.]+).([0-9.]+)/', $clusters[$i][$a])
                                || preg_match('/^Hlas ([0-9.]+).([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^hlas ([0-9.]+).([0-9.]+)/', $clusters[$i][$a])
                                || preg_match('/Hlas([0-9.]+)/', $clusters[$i][$a]) || preg_match('/hlas([0-9.]+)/', $clusters[$i][$a])
                                || preg_match('/Hlas ([0-9.]+)/', $clusters[$i][$a]) || preg_match('/hlas ([0-9.]+)/', $clusters[$i][$a])){
                            
                            echo "Přeskakuji zbytečný řádek.";
                            echo "\r\n";
                        }else if(((strpos($clusters[$i][$a], 'Zasedán') || strpos($clusters[$i][$a], 'zasedán') || strpos($clusters[$i][$a], 'Zasedan') || strpos($clusters[$i][$a], 'zasedan')) ||
                                (strpos($clusters[$i][$a], 'Jednán') || strpos($clusters[$i][$a], 'jednán') || strpos($clusters[$i][$a], 'Jednan') || strpos($clusters[$i][$a], 'jednan'))) &&
                                !((strpos($clusters[$i][$a], 'zprávu')) || (strpos($clusters[$i][$a], 'Zprávu')) || (strpos($clusters[$i][$a], 'Zpráva')) || (strpos($clusters[$i][$a], 'Zpráva')))){
                            if ($event == "empty") {
                                $event = rtrim(str_replace("  ","",$clusters[$i][$a]),"\n\r");
                            }
                        }else if(strpos($clusters[$i][$a], 'edsedá') || strpos($clusters[$i][$a], 'edseda')){
                            if ($chairman == "empty") {
                                $chairman = rtrim(str_replace("  ","",$clusters[$i][$a]),"\n\r");
                            }
                        }else if(preg_match('/HLASOVÁNÍ Č. ([0-9.]+) /i', $clusters[$i][$a])||preg_match('/HLASOVÁNÍ č. ([0-9.]+) /i', $clusters[$i][$a])
                                || preg_match('/HLASOVANI C. ([0-9.]+) /i', $clusters[$i][$a])||preg_match('/HLASOVANI c. ([0-9.]+) /i', $clusters[$i][$a])
                                || preg_match('/HLASOVÁNÍ h. ([0-9.]+) /i', $clusters[$i][$a])||preg_match('/HLASOVÁNÍ H. ([0-9.]+) /i', $clusters[$i][$a])
                                || preg_match('/HLASOVANI h. ([0-9.]+) /i', $clusters[$i][$a])||preg_match('/HLASOVANI H. ([0-9.]+) /i', $clusters[$i][$a])
                                || preg_match('/PREZENCE h. ([0-9.]+) /i', $clusters[$i][$a])||preg_match('/PREZENCE H. ([0-9.]+) /i', $clusters[$i][$a])
                                || preg_match('/HLASOVANM h. ([0-9.]+) /i', $clusters[$i][$a])||preg_match('/HLASOVANM H. ([0-9.]+) /i', $clusters[$i][$a])){
                            if ($state == "empty") {
                                $state = rtrim(str_replace("  ","",$clusters[$i][$a]),"\n\r");
                            }
                        }else if(preg_match('/([0-9.]+).([0-9.]+).([0-9.]+) ([0-9.]+):([0-9.]+):([0-9.]+)/', $clusters[$i][$a])){
                            if ($dateString == "empty") {
                                $dateString = rtrim(str_replace("  ","",$clusters[$i][$a]),"\n\r");
                            }
                        }else if(preg_match('/([0-9.]+).*[A-Za-z]/', $clusters[$i][$a])){
                            if ($type == "empty") {
                                $type = rtrim(str_replace("  ","",$clusters[$i][$a]),"\n\r");
                            }
                        }else{
                            $voteAboveDescription[] = $clusters[$i][$a];
                        }
                    }
                }else{
                    if(preg_match('/^Stránka ([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^stránka ([0-9.]+)/', $clusters[$i][$a])
                            || preg_match('/^Stranka ([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^stranka ([0-9.]+)/', $clusters[$i][$a])
                            || preg_match('/^Hlas([0-9.]+).([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^hlas([0-9.]+).([0-9.]+)/', $clusters[$i][$a])
                            || preg_match('/^Hlas ([0-9.]+).([0-9.]+)/', $clusters[$i][$a]) || preg_match('/^hlas ([0-9.]+).([0-9.]+)/', $clusters[$i][$a])){

                        echo "Přeskakuji zbytečný řádek.";
                        echo "\r\n";
                    }else{
                        $voteDescription[] = $clusters[$i][$a];
                    }
                }
            }
        }
        
//        print "<pre>";
//        print_r($voteAboveDescription);
//        print "</pre>";
//        echo "\r\n";
//        print "<pre>";
//        print_r($votedDecision);
//        print "</pre>";
//        echo "\r\n";
//        print "<pre>";
//        print_r($voteDescription);
//        print "</pre>";
//        echo "\r\n";
//        var_dump($event);
//        echo "\r\n";
//        var_dump($chairman);
//        echo "\r\n";
//        var_dump($state);
//        echo "\r\n";
//        var_dump($dateString);
//        echo "\r\n";
//        var_dump($type);
        
        
        $finAboveDescription = "";
        foreach ($voteAboveDescription as $item) {
            $item = str_replace('"',"",(string)$item);
            $finAboveDescription .= $item;
        }
        
        
        $finDescription = "";
        foreach ($voteDescription as $item) {
            $item = str_replace('"',"",(string)$item);
            $item = str_replace("'","",(string)$item);
            $finDescription .= $item;
        }
//        var_dump($sendSessionInfo);
        $sendSessionInfo = [$event,$chairman,$state,strtotime($dateString),$type,$finDescription,$finAboveDescription,$sittingId];
        $APM->addVotingSession($sendSessionInfo);
        $sessionId = $APM->getVotingSessionByEventChairmanState(strtotime($dateString),$sittingId);
//        var_dump($sessionId);
//        echo "\r\n";
//        echo "\r\n";
//        echo "\r\n";
//        print "<pre>";
//        print_r($sendSessionInfo);
//        print "</pre>";
//        echo "\r\n";
        
        $didTheTabsProcess = FALSE;
        $tabsProcessHasBeenSet = FALSE;
        foreach ($votedDecision as $item) {
            if($item){
                if(preg_match('/\t/', $item)){
                    $voteDataArray = preg_split("/[\t]/", $item);
                    if(!$tabsProcessHasBeenSet){
                        $didTheTabsProcess = TRUE;
                        $tabsProcessHasBeenSet = TRUE;
                    }
                }else{
//                    for ($e=1;$e<200;$e++) {
//                        $item = str_replace(" ".$e." ","    ",(string)$item);
//                    }
                    $voteDataArray = explode("  ",$item);
                    if(!$tabsProcessHasBeenSet){
                        $tabsProcessHasBeenSet = TRUE;
                    }
                }
//                var_dump($voteDataArray);
                $voteDataNotEmpty = [];
                foreach ($voteDataArray as $value) {
                    if ($value !== "" && $value !== "\r" && $value !== "\n" && $value !== "\r\n" && $value !== "\n\r"
                        && $value !== " " && $value !== " \r" && $value !== " \n" && $value !== " \r\n" && $value !== " \n\r") {
                        if(preg_match('/^hlas/', $value)||preg_match('/^Hlas/', $value)){
                            
                        }else{
                            $voteDataNotEmpty[] = $value;
                        }
                    }
                }
                if(!$didTheTabsProcess){
                    if(sizeof($voteDataNotEmpty)==3 || sizeof($voteDataNotEmpty)==2){
                        if(strlen($voteDataNotEmpty[2]) == 0){
                            $voteDataNotEmpty[2] = "NEPŘÍTOMEN";
                        }
                        $voteDataNotEmpty[3] = $voteDataNotEmpty[2];
                        $voteDataNotEmpty[1] = ltrim($voteDataNotEmpty[1], " ");
                        $voteDataNotEmpty[1] = rtrim($voteDataNotEmpty[1], " ");
                        $splitAr = explode(" ",$voteDataNotEmpty[1]);
                        if($splitAr[0] !== NULL){
                            $voteDataNotEmpty[1] = $splitAr[0];
                        }else{
                            $voteDataNotEmpty[1] = $voteDataNotEmpty[1];
                        }
                        if($splitAr[1] !== NULL){
                            $voteDataNotEmpty[2] = $splitAr[1];
                        }else{
                            $voteDataNotEmpty[2] = $voteDataNotEmpty[1];
                        }
                    }
                }else{
                    if(sizeof($voteDataNotEmpty)==3){
                        $voteDataNotEmpty[3] = $voteDataNotEmpty[2];
                        $voteDataNotEmpty[2] = $voteDataNotEmpty[1];
                        $voteDataNotEmpty[1] = "Člen není přiřazen k žádné straně";
                    }
                }
                
                $voteDataNotEmpty[0] = str_replace("   ","",(string)$voteDataNotEmpty[0]);
                $voteDataNotEmpty[0] = str_replace("  ","",(string)$voteDataNotEmpty[0]);
                $voteDataNotEmpty[0] = ltrim($voteDataNotEmpty[0], " ");
                $voteDataNotEmpty[0] = rtrim($voteDataNotEmpty[0], " ");
                $voteDataNotEmpty[0] = rtrim($voteDataNotEmpty[0], "\n\r");

                $voteDataNotEmpty[1] = str_replace("   ","",(string)$voteDataNotEmpty[1]);
                $voteDataNotEmpty[1] = str_replace("  ","",(string)$voteDataNotEmpty[1]);
                $voteDataNotEmpty[1] = ltrim($voteDataNotEmpty[1], " ");
                $voteDataNotEmpty[1] = rtrim($voteDataNotEmpty[1], " ");
                $voteDataNotEmpty[1] = rtrim($voteDataNotEmpty[1], "\n\r");

                if($didTheTabsProcess){
                    $voteDataNotEmpty[3] = str_replace("   ","",(string)$voteDataNotEmpty[3]);
                    $voteDataNotEmpty[3] = str_replace("  ","",(string)$voteDataNotEmpty[3]);
                    $voteDataNotEmpty[3] = ltrim($voteDataNotEmpty[3], " ");
                    $voteDataNotEmpty[3] = rtrim($voteDataNotEmpty[3], " ");
                    $voteDataNotEmpty[3] = rtrim($voteDataNotEmpty[3], "\n\r");
                }else{
                    $voteDataNotEmpty[2] = str_replace("   ","",(string)$voteDataNotEmpty[2]);
                    $voteDataNotEmpty[2] = str_replace("  ","",(string)$voteDataNotEmpty[2]);
                    $voteDataNotEmpty[2] = ltrim($voteDataNotEmpty[2], " ");
                    $voteDataNotEmpty[2] = rtrim($voteDataNotEmpty[2], " ");
                    $voteDataNotEmpty[2] = rtrim($voteDataNotEmpty[2], "\n\r");
                }

                
//                echo "\r\n";
//                echo "\r\n";
//                var_dump($voteDataNotEmpty);
//                var_dump(sizeof($voteDataNotEmpty)==4);
//                if(sizeof($voteDataNotEmpty)!==4){
//                    var_dump($voteDataNotEmpty);
//                }
//                echo "\r\n";
//                echo "\r\n";
                
                
                
                if($didTheTabsProcess){
                    if(sizeof($voteDataNotEmpty) !== 0){
                        if($voteDataNotEmpty[0] !== "" && $voteDataNotEmpty[1] !== ""){
//                            var_dump($voteDataNotEmpty);
    //                        var_dump($voteDataNotEmpty[0]);
    //                        var_dump($voteDataNotEmpty[1]);
    //                        var_dump($voteDataNotEmpty[3]);
    //                        echo "\n\r";
    //                        echo "\n\r";
                            $APM->addVotedDecision($voteDataNotEmpty[0],$voteDataNotEmpty[1],$voteDataNotEmpty[3],$sessionId);
                        }
                    }
                }else{
                    if(sizeof($voteDataNotEmpty) !== 0){
                        if($voteDataNotEmpty[0] !== "" && $voteDataNotEmpty[1] !== ""){
//                            var_dump($voteDataNotEmpty);
//                            var_dump($voteDataNotEmpty[0]);
//                            var_dump($voteDataNotEmpty[1]);
//                            var_dump($voteDataNotEmpty[3]);
                            echo "\n\r";
                            echo "\n\r";
                            $APM->addVotedDecision($voteDataNotEmpty[0],$voteDataNotEmpty[1],$voteDataNotEmpty[3],$sessionId);
                        }
                    }
                }
            }
        }
        
    }
}


echo json_encode("Proces dokončen");
exit();