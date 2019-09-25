<?php


namespace Manager;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;

/**
 * Třída pro správu login stránky.
 * @package Manager
 */
class AddPageManager extends BaseManager {

    /** Konstanty pro manipulaci s modelem */
    const
    //Table 1
        TABLE_NAME = "voting_sessions",
        COLUMN_ID = "id",
        COLUMN_EVENT = "event",
        COLUMN_CHAIRMAN = "chairman",
        COLUMN_STATE = "state",
        COLUMN_DATE = "date",
        COLUMN_TYPE = "type",
        COLUMN_DESCRIPTION = "description",
    //Table 2
        TABLE_NAME_2 = "voted",
        COLUMN_ID_2 = "id",
        COLUMN_NAME_2 = "name",
        COLUMN_GROUP_NAME_2 = "groupName",
        COLUMN_DECISION_2 = "decision",
        COLUMN_SESSION_ID = "sessionId",
    //Table 3
        TABLE_NAME_3 = "cities",
        COLUMN_ID_3 = "id",
        COLUMN_NAME_3 = "name",
    //Table 4
        TABLE_NAME_4 = "sittings",
        COLUMN_ID_4 = "id",
        COLUMN_NAME_4 = "name",
        COLUMN_START_4 = "start",
        COLUMN_END_4 = "end",
        COLUMN_CITY_ID_4 = "cityId",
    //Table 5
        TABLE_NAME_5 = "voting_sessions",
        COLUMN_ID_5 = "id",
        COLUMN_EVENT_5 = "event",
        COLUMN_CHAIRMAN_5 = "chairman",
        COLUMN_STATE_5 = "state",
        COLUMN_DATE_5 = "date",
        COLUMN_TYPE_5 = "type",
        COLUMN_DESCRIPTION_5 = "description",
        COLUMN_ABOVE_DESCRIPTION_5 = "above_description",
        COLUMN_SITTING_ID_5 = "sittings_id";
    
    
    public function getsessionById($id) {
        $sessionInfo = array_map('iterator_to_array',$this->database->query(
                "SELECT * FROM "
                .self::TABLE_NAME_5.
                " WHERE "
                .self::COLUMN_ID_5."=".(int)$id."")->fetchAll())[0];
        return $sessionInfo;
    }
    
    public function countVotedAndNotForOneSitting($sessionIds) {
        $proslo = 0;
        $neproslo = 0;
        foreach ($sessionIds as $sesId) {
            $hlasovaloCelkem = 0;
            $hlasovaloPro = 0;
            $result = array_map('iterator_to_array',$this->database->query(
                "SELECT COUNT(*) FROM "
                .self::TABLE_NAME_2." WHERE (". self::COLUMN_SESSION_ID." = ".$sesId. ")")->fetchAll());
            $hlasovaloCelkem = $result[0]["COUNT(*)"];
            $result2 = array_map('iterator_to_array',$this->database->query(
                "SELECT COUNT(*) FROM "
                .self::TABLE_NAME_2." WHERE (". self::COLUMN_SESSION_ID." = ".$sesId. ") AND ("
                . self::COLUMN_DECISION_2." = 'Pro')")->fetchAll());
            $hlasovaloPro = $result2[0]["COUNT(*)"];
            if($hlasovaloPro > ($hlasovaloCelkem/2)){
                $proslo+=1;
            }else{
                $neproslo+=1;
            }
        }
        $finSend = [$proslo,$neproslo];
        return $finSend;
    }
    
    public function quickSearch($info) {
        $searchWord = (string)$info['word'];
        $finalResults = [];
        $SessionsResult = [];
        $SessionsResult = array_map('iterator_to_array',$this->database->query(
                "SELECT * FROM "
                .self::TABLE_NAME_5.
                " WHERE ("
                . self::COLUMN_DESCRIPTION_5.
                " LIKE '%".$searchWord."%'"
                . " OR "
                . self::COLUMN_TYPE_5.
                " LIKE '%".$searchWord."%'"
                . " )")->fetchAll());
        if(sizeof($SessionsResult) > 0){
            $finalResults[0] = $SessionsResult;
        }
        
        $VotedResult = [];
        $VotedResult = array_map('iterator_to_array',$this->database->query(
            "SELECT * FROM "
            .self::TABLE_NAME_2.
            " WHERE ("
            . self::COLUMN_NAME_2.
            " LIKE '%".$searchWord."%'"
            . " OR "
            . self::COLUMN_GROUP_NAME_2.
            " LIKE '%".$searchWord."%'"
            . " )")->fetchAll());
        if(sizeof($VotedResult) > 0){
            $finalResults[1] = $VotedResult;
        }
        return $finalResults;
        
    }
    
    public function getPersonsBySessions($sessionIds) {
        $personCluster=[];
        if($sessionIds == "ALL"){
            $personInfo = array_map('iterator_to_array',$this->database->query(
                    "SELECT * FROM "
                    .self::TABLE_NAME_2.
                    " ORDER BY " .self::COLUMN_GROUP_NAME_2)->fetchAll());
            $personCluster[] = $personInfo;
        }else{
            foreach ($sessionIds as $sessionId) {
                $personInfo = array_map('iterator_to_array',$this->database->query(
                        "SELECT * FROM "
                        .self::TABLE_NAME_2.
                        " WHERE "
                        .self::COLUMN_SESSION_ID."=".(int)$sessionId." ORDER BY " .self::COLUMN_GROUP_NAME_2)->fetchAll());
                $personCluster[] = $personInfo;
            }
        }
        return $personCluster;
    }
    
    public function getBasicSessionData() {
        $sessionInfo = array_map('iterator_to_array',$this->database->query(
                "SELECT id,type,date,description FROM "
                .self::TABLE_NAME_5."")->fetchAll());
        return $sessionInfo;
    }
    
    public function getBasicSittingsData() {
        $sittingInfo = array_map('iterator_to_array',$this->database->query(
                "SELECT * FROM "
                .self::TABLE_NAME_4." ORDER BY ".self::COLUMN_START_4)->fetchAll());
        return $sittingInfo;
    }
    
    public function getSessionIdsByType($types) {
        $sessionCluster=[];
        foreach ($types as $type) {
            $sessionInfo = array_map('iterator_to_array',$this->database->query(
                    "SELECT id FROM "
                    .self::TABLE_NAME_5.
                    " WHERE "
                    .self::COLUMN_TYPE_5."='".$type."'")->fetchAll());
            $sessionCluster[] = $sessionInfo[0]["id"];
        }
        return $sessionCluster;
    }
    
    public function getSessionsByNewSittings($sittingTypes) {
        if(sizeof($sittingTypes)==0){
            $sessionCluster=[];
                $sessionInfo = array_map('iterator_to_array',$this->database->query(
                        "SELECT * FROM "
                        .self::TABLE_NAME_5)->fetchAll());
                $sessionCluster[] = $sessionInfo;
            return $sessionCluster;
        }else{
            $checkZasedani = False;
            //echo 'sittingTypes je prázdné';
            $sessionCluster=[];
            foreach ($sittingTypes as $sittingType) {
                $sessionInfo = array_map('iterator_to_array',$this->database->query(
                        "SELECT * FROM "
                        .self::TABLE_NAME_5.
                        " WHERE "
                        .self::COLUMN_TYPE_5." LIKE '%".$sittingType."%'")->fetchAll());
                $sessionCluster[] = $sessionInfo;
            }
            return $sessionCluster;
        }
    }
    
    public function getSessionsByNewSitting($sittingType) {
        $sessionInfo = array_map('iterator_to_array',$this->database->query(
                "SELECT * FROM "
                .self::TABLE_NAME_5.
                " WHERE "
                .self::COLUMN_TYPE_5." LIKE '%".$sittingType."%'")->fetchAll());
        return $sessionInfo;
    }
    
    public function getSessionsBySittings($sittingIds) {
        $sessionCluster=[];
        foreach ($sittingIds as $sittingId) {
            $sessionInfo = array_map('iterator_to_array',$this->database->query(
                    "SELECT * FROM "
                    .self::TABLE_NAME_5.
                    " WHERE "
                    .self::COLUMN_SITTING_ID_5."=".(int)$sittingId."")->fetchAll());
            $sessionCluster[] = $sessionInfo;
        }
        return $sessionCluster;
    }
    
    public function getpersonalVotedCluster($name) {
        $votedInfo = array_map('iterator_to_array',$this->database->query(
                "SELECT * FROM "
                .self::TABLE_NAME_2.
                " WHERE "
                .self::COLUMN_NAME_2."='".$name."'")->fetchAll());
        return $votedInfo;
    }
    
    public function getSessionsByIds($sittingIds) {
        $sessionCluster=[];
        foreach ($sittingIds as $sittingId) {
            $sessionInfo = array_map('iterator_to_array',$this->database->query(
                    "SELECT * FROM "
                    .self::TABLE_NAME_5.
                    " WHERE "
                    .self::COLUMN_ID_5."=".(int)$sittingId."")->fetchAll());
            $sessionCluster[] = $sessionInfo;
        }
        return $sessionCluster;
    }
    
    public function getZastupitelstvaByCityName($cityName) {
        $cityId = array_map('iterator_to_array',$this->database->query(
                "SELECT "
                .self::COLUMN_ID_3.
                " FROM "
                .self::TABLE_NAME_3.
                " WHERE".
                " MATCH (". self::COLUMN_NAME_3 .")".
                " AGAINST ('".(string)$cityName."' IN NATURAL LANGUAGE MODE)")->fetchAll())[0]['id'];
        $sittings = array_map('iterator_to_array',$this->database->query(
                "SELECT * FROM "
                .self::TABLE_NAME_4.
                " WHERE "
                .self::COLUMN_CITY_ID_4."=".(int)$cityId)->fetchAll());
        return $sittings;
    }
    
    public function getSessions(){
        return $this->database->query("SELECT * FROM ".self::TABLE_NAME."");
    }
    
    public function getVotesBySessions($sessionIds){
        $send = [];
        for($i=0;$i<sizeof($sessionIds);$i++)
        {
            $result = $this->database->query("SELECT * FROM ".self::TABLE_NAME_2." WHERE sessionId=".(int)$sessionIds[$i]);
            array_push($send, $result);
        }
        return $send;
    }
    
    public function getVotesByPersonNames($name,$sessionId){
        return $result = $this->database->query("SELECT * FROM ".self::TABLE_NAME_2." WHERE sessionId=".(int)$sessionId." AND name='".(string)$name."'");
    }
    
    public function getVotesByPersonName($name){
        return $result = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME_2." WHERE ".self::COLUMN_NAME_2."='".(string)$name."'")->fetchAll());
    }
    
    public function getVotesByPS($PSName){
        $result = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME_2." WHERE ".self::COLUMN_GROUP_NAME_2."='".(string)$PSName."'")->fetchAll());
        return $result;
    }
    
    public function getSessionsByType($SesName){
        $result = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME." WHERE ".self::COLUMN_TYPE."='".(string)$SesName."'")->fetchAll());
        return $result;
    }
    
    public function getVotesBySessionId($sessionId){
        $result = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME_2." WHERE sessionId=".(int)$sessionId)->fetchAll());
        return $result;
    }
    
}