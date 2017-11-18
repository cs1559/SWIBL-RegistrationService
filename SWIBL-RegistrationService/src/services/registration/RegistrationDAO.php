<?php
namespace swibl\services\registration;

use Exception;
use swibl\core\Database;
use swibl\core\DateUtil;

class RegistrationDAO {
    
    private $database = null;
    
    /**
     * Private constructor to ensure that the object cannot be instantiated by a client.
     */
    private function __construct() {
    }
    
    static function getInstance(Database $db) {
        static $instance;
        if (!is_object( $instance )) {
            $instance = new RegistrationDAO();
        }
        $instance->setDatabase($db);
        return $instance;
    }
    
    function getDatabase() {
        return $this->database;
    }
    function setDatabase($db) {
        $this->database = $db;
    }
    
    function get($id) {
        
        $db = $this->getDatabase();
        $db->setQuery("select * from joom_jleague_divmap where id = " . $id);
        try {
            $result = $db->loadObject();
            $builder = new RegistrationBuilder();
            $object = $builder->build($result);
        } catch (\Exception $e) {
            throw $e;
        }
        return $object;
    }
    
    
    /**
     * This method wil return all registrations for a given season.
     * @param unknown $season
     * @throws Exception
     * @return unknown
     */
    function getRegistrations($season) {
        
        if (!is_numeric($season)) {
            throw new \Exception("Invalid data format - NOT NUMERIC");
        }
        $db = $this->getDatabase();
        $db->setQuery("select * from joom_jleague_divmap where season = " . $season . " order by agegroup" );
        try {
            $results = $db->loadObjectList();
            $registrations = RegistrationHelper::bindArray($results);
            
        } catch (\Exception $e) {
            throw $e;
        }
        return $registrations;
    }
    
    function delete($id) {
        
        $service = RegistrationService::getInstance();
        $logger = $service->getLogger();
        
        $logger->debug("Attempting to DELETE record " . $id);
        
        $db = $this->getDatabase();
        $db->setQuery("delete from joom_jleague_divmap where id = " . $id);
        try {
            $result = $db->query();
            $logger->info("Total records deleted is " . $db->getAffectedRows());
            if ($db->getAffectedRows() == 0) {
                throw new Exception("NO RECORDS DELETED");
            }
        } catch (\Exception $e) {
            $logger->error($db->getErrorMsg());
            throw $e;
        }
        return true;
    }
    
    function insert(Registration $obj) {
        
        $service = RegistrationService::getInstance();
        $logger = $service->getLogger();
        
        // Throw an exception if the game object ID has a value other than 0 (zero)
        if ($obj->getId()) {
            throw new \Exception("Insert not allowed.  ID already populated");
        }
        
        $logger->debug("Attempting to INSERT record " . $obj->getId());
        
        $db = $this->getDatabase();
        $newDate = DateUtil::dateConvertForInput($obj->getRegistrationDate());
        
        $logger->debug("after date conversion " . $obj->getId());
        
        $query = 'INSERT INTO joom_jleague_divmap (id, division_id, season, team_id, name, address, city, state, email, phone, cellphone, '
            . 'teamname, agegroup, existingteam, published, paid, confnum, confirmed, tournament, allstarevent, regdate, registeredby, '
                . 'divclass, tosack, ipaddr)'
                    . ' VALUES (0,'
                . '"' . $obj->getDivisionId(). '",'
                . '"' . $obj->getSeasonId() . '",'
                . '"' . $obj->getTeamId() . '",'
                . '"' . $obj->getName() . '",'
                . '"' . $obj->getAddress() . '",'
                . '"' . $obj->getCity() . '",'
                . '"' . $obj->getState() . '",'
                . '"' . $obj->getEmail() . '",'
                . '"' . $obj->getPhone() . '",'
                . '"' . $obj->getCellPhone() . '",'
                . '"' . $obj->getTeamName() . '",'
                . '"' . $obj->getAgeGroup() . '",'
                . '"' . $obj->getExistingTeam() . '",'
                . '"' . $obj->getPublished() . '",'
                . '"' . $obj->isPaid() . '",'
                . '"' . $obj->getConfirmationNumber(). '",'
                . '"' . $obj->isConfirmed() . '",'
                . '"' . $obj->isPlayingInTournament() . '",'
                . '"' . $obj->isPlayingInAllStarEvent() . '", '      
                . '     date("' . $newDate. '"), '
                . '"' . $obj->getRegisteredBy() . '",'
                . '"' . $obj->getDivisionClass() . '",'
                . '"' . $obj->getTosAck() . '",'
                . '"' . $obj->getIpAddress() . '"'
            .  ')';
                                                                                                                                                                                                    
        $logger->debug($query);
                                                                                                                                                                                                
        if (!$db->query($query)) {
            $logger->error($db->getErrorMsg());
            throw new Exception($db->getErrorMsg());
        } else {
            $logger->info("Record ID " . $db->insertId() . " has been INSERTED");
            return $db->insertId();
        }
                                                                                                    
    }

    function update(Registration $obj) {
        $service = RegistrationService::getInstance();
        $logger = $service->getLogger();
        
        $logger->debug("Attempting to UPDATE record " . $obj->getId());
        
        $db = $this->getDatabase();
        $newDate = DateUtil::dateConvertForInput($obj->getRegistrationDate());
        
        $query = 'update  joom_jleague_divmap set '
            . ' division_id = "' . $obj->getDivisionId(). '", '
            . ' season = "' . $obj->getSeasonId(). '", '
            . ' team_id = "' . $obj->getTeamId(). '", '
            . ' teamname = "' . $obj->getTeamName(). '", '
            . ' name = "' . $obj->getName(). '", '
            . ' city = "' . $obj->getCity(). '", '
            . ' state = "' . $obj->getState(). '", '
            . ' email = "' . $obj->getEmail(). '", '
            . ' phone = "' . $obj->getPhone(). '", '
            . ' cellphone = "' . $obj->getCellPhone(). '", '
            . ' agegroup = "' . $obj->getAgeGroup(). '", '
            . ' existingteam = "' . $obj->getExistingTeam(). '", '
            . ' paid = "' . $obj->isPaid() . '", '
            . ' confirmed = "' . $obj->isConfirmed(). '", '
            . ' tournament = "' . $obj->isPlayingInTournament() . '", '
            . ' allstarevent = "' . $obj->isPlayingInAllStarEvent() . '", '
        //			. ' confnum = "' . $obj->getConfirmationNumber() . '", '
            . ' divclass = "' . $obj->getDivisionClass() . '", '
            . ' requestedclass = "' . $obj->getRequestedClassification() . '",'
            . ' tosack = "' . $obj->getTosAck() . '", '
            . ' ipaddr = "' . $obj->getIpAddress() . '", '
            . ' published = ' . $obj->getPublished()
            . ' where id = ' . $obj->getId();
        
            $logger->debug($query);
            
            if (!$db->query($query)) {
                $logger->error($db->getErrorMsg());
                throw new Exception($db->getErrorMsg());
            } else {
                $logger->info("Record ID " . $obj->getId() . " has been updated");
                return true;
            }	  
    }
    
    
    function confirm($id) {
        
        $service = RegistrationService::getInstance();
        $logger = $service->getLogger();
        
        $logger->debug("Attempting to UPDATE (CONFIRM) record " . $id);
        
        $db = $this->getDatabase();
        $db->setQuery("update joom_jleague_divmap set confirmed = 1 where id = " . $id);
        try {
            $result = $db->query();
            $logger->info("Total records updated is " . $db->getAffectedRows());
            if ($db->getAffectedRows() == 0) {
                throw new Exception("NO RECORDS CONFIRMED");
            }
        } catch (\Exception $e) {
            $logger->error($db->getErrorMsg());
            throw $e;
        }
        return true;
    }
    
}