<?php


namespace Manager;
use Nette\Database\Table\IRow;
use Nette\Database\Table\Selection;
use Nette\Utils\ArrayHash;

/**
 * Třída pro správu login stránky.
 * @package Manager
 */
class AdministraceManager extends BaseManager {

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
        COLUMN_ABOVE_DESCRIPTION = "above_description",
        COLUMN_SITTINGS_ID = "sittings_id",
    //Table 2
        TABLE_NAME_2 = "voted",
        COLUMN_ID_2 = "id",
        COLUMN_NAME_2 = "name",
        COLUMN_GROUP_NAME_2 = "groupName",
        COLUMN_DECISION_2 = "decision",
        COLUMN_SESSION_ID_2 = "sessionId",
    //Table 3
        TABLE_NAME_3 = "sittings",
        COLUMN_ID_3 = "id",
        COLUMN_NAME_3 = "name",
        COLUMN_START_3 = "start",
        COLUMN_END_3 = "end",
        COLUMN_CITY_ID_3 = "cityId",
    //Table 4
        TABLE_NAME_4 = "cities",
        COLUMN_ID_4 = "id",
        COLUMN_NAME_4 = "name";
    
    
    
    
    public function addVotedDecision($name,$gname,$decision,$sessionId) {
        $this->database->query("INSERT INTO ".self::TABLE_NAME_2." ("
                .self::COLUMN_NAME_2.","
                .self::COLUMN_GROUP_NAME_2.","
                .self::COLUMN_DECISION_2.","
                .self::COLUMN_SESSION_ID_2.") VALUES ('"
                .(string)$name."','"
                .(string)$gname."','"
                .(string)$decision."',"
                .(int)$sessionId.")");
        return "splněno";
    }
    
    public function getVotingSessionByEventChairmanState($time,$sitId) {
        $sessionId = array_map('iterator_to_array',$this->database->query("SELECT * FROM "
                .self::TABLE_NAME." WHERE "
                .self::COLUMN_DATE."=".(int)$time . " AND "
                .self::COLUMN_SITTINGS_ID."=".(int)$sitId)->fetchAll())[0]["id"];
        return $sessionId;
    }
    
    public function addVotingSession($sessionInfo) {
        $this->database->query("INSERT INTO ".self::TABLE_NAME." ("
                .self::COLUMN_EVENT.","
                .self::COLUMN_CHAIRMAN.","
                .self::COLUMN_STATE.","
                .self::COLUMN_DATE.","
                .self::COLUMN_TYPE.","
                .self::COLUMN_DESCRIPTION.","
                .self::COLUMN_ABOVE_DESCRIPTION.","
                .self::COLUMN_SITTINGS_ID.") VALUES ('"
                .(string)$sessionInfo[0]."','"
                .(string)$sessionInfo[1]."','"
                .(string)$sessionInfo[2]."',"
                .(int)$sessionInfo[3].",'"
                .(string)$sessionInfo[4]."','"
                .(string)$sessionInfo[5]."','"
                .(string)$sessionInfo[6]."',"
                .(int)$sessionInfo[7].")");
        return "splněno";
    }
    
    public function removeCity($cityId) {
        $sittings = self::getSittingsByCityId($cityId);
        foreach ($sittings as $sitting) {
            $sitId = $sitting["id"];
            $sessions = self::getSessionsBySittingId($sitId);
            foreach ($sessions as $session) {
                $sesId = $session["id"];
                self::removeVotesBySessionId($sesId);
                self::removeSessionById($sesId);
            }
            self::removeSitting($sitId);
        }
        $this->database->query("DELETE FROM ".self::TABLE_NAME_4." WHERE ".self::COLUMN_ID_4."=".$cityId);
        return "splněno";
    }
    
    public function removeSessionById($sessionId) {
        $this->database->query("DELETE FROM ".self::TABLE_NAME." WHERE ".self::COLUMN_ID."=".$sessionId);
        return "splněno";
    }
    
    public function removeVotesBySessionId($sessionId) {
        $this->database->query("DELETE FROM ".self::TABLE_NAME_2." WHERE ".self::COLUMN_SESSION_ID_2."=".$sessionId);
        return "splněno";
    }
    
    public function removeSitting($sittingId) {
        $this->database->query("DELETE FROM ".self::TABLE_NAME_3." WHERE ".self::COLUMN_ID_3."=".$sittingId);
        return "splněno";
    }
    
    public function editCity($cityInfo) {
        $this->database->query("UPDATE ".self::TABLE_NAME_4." SET ".self::COLUMN_NAME_4."='".(string)$cityInfo["name"]."' WHERE ".self::COLUMN_ID_4."=".(int)$cityInfo["id"]);
        return "splněno";
    }
    
    public function getVotesBySessionId($sessionId) {
        $votes = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME_2." WHERE ".self::COLUMN_SESSION_ID_2."=".(int)$sessionId)->fetchAll());
        return $votes;
    }
    
    public function getSessionsBySittingId($sittingId) {
        $sessions = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME." WHERE ".self::COLUMN_SITTINGS_ID."=".(int)$sittingId)->fetchAll());
        return $sessions;
    }
    
    public function addSitting($sittingInfo) {
        $this->database->query("INSERT INTO ".self::TABLE_NAME_3." (".self::COLUMN_NAME_3.",".self::COLUMN_START_3.",".self::COLUMN_END_3.",".self::COLUMN_CITY_ID_3.") VALUES ('".(string)$sittingInfo["name"]."','".(string)$sittingInfo["from"]."','".(string)$sittingInfo["to"]."',".(int)$sittingInfo["cityId"].")");
        return "splněno";
    }
    
    public function addCity($cityName) {
        $this->database->query("INSERT INTO ".self::TABLE_NAME_4." (".self::COLUMN_NAME_4.") VALUES ('".(string)$cityName."')");
        return "splněno";
    }
    
    public function getSessions($yearSelected){
        $sessions = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME."")->fetchAll());
        
        $send = [];
        for($a=0;$a<sizeof($yearSelected);$a++){
            $sessionsToSend = [];
            $uniqueSessionNames = [];
            for($i=0;$i<sizeof($sessions);$i++){
                if((int)date('Y', $sessions[$i]["date"]) == (int)$yearSelected[$a]){
                    array_push($sessionsToSend, $sessions[$i]);
                    if (!(in_array($sessions[$i]["event"], $uniqueSessionNames))) {
                        array_push($uniqueSessionNames, $sessions[$i]["event"]);
                    }
                }
            }
            array_push($send, $uniqueSessionNames);
            array_push($send, $sessionsToSend);
        }
        return $send;
    }
    
    public function getSittingsByCityId($cityId) {
        $sittings = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME_3." WHERE ".self::COLUMN_CITY_ID_3."=".(int)$cityId)->fetchAll());
        return $sittings;
    }
    
    public function getCities() {
        $citties = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME_4."")->fetchAll());
        return $citties;
    }
    
    public function getSittings(){
        $sittings = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME_3."")->fetchAll());
        return $sittings;
    }
    
    public function getVotesByThemes($themes){
        $send = [];
        $names = [];
        $data = [];
        for($i=0;$i<sizeof($themes);$i++)
        {
            $res = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME." WHERE ".self::COLUMN_TYPE."='".$themes[$i]."'")->fetchAll());
            $id = $res[0]["id"];
            
            $res2 = array_map('iterator_to_array',$this->database->query("SELECT * FROM ".self::TABLE_NAME_2." WHERE ".self::COLUMN_SESSION_ID_2."=".(int)$id)->fetchAll());
            array_push($data, $res2);
            for($i=0;$i<sizeof($res2);$i++){
                if(!(in_array($res2[$i]["name"], $names))){
                    array_push($names, $res2[$i]["name"]);
                }
            }
        }
        array_push($send, $names);
        array_push($send, $data);
        return $send;
    }
    
    public function getVotesByPersonNames($name,$sessionId){
        return $result = $this->database->query("SELECT * FROM ".self::TABLE_NAME_2." WHERE sessionId=".(int)$sessionId." AND name='".(string)$name."'");
    }
    
}