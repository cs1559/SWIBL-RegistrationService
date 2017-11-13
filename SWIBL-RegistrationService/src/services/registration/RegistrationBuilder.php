<?php 
namespace swibl\services\registration;


/**
 * This object is used to build a GAME object.
 */
use Exception;
use swibl\core\ObjectBuilder;

class RegistrationBuilder extends ObjectBuilder {
   
    /**
     * The fieldMap defines the table column name and the objects SETTER method
     * @var array
     */
    
    /*
     *            

          
          
     */
    var $fieldMap = array(
        "id" => "setId",
        "division_id" => "setDivisionId",
        "season" => "setSeasonId",
        "team_id" => "setTeamId",
        "name" => "setName",
        "address" => "setAddress",
        "city" => "setCity",
        "state" => "setState",
        "email" => "setEmail",
        "phone" => "setPhone",   
        "cellphone" => "setCellPhone",
        "teamname" => "setTeamName",
        "agegroup" => "setAgeGroup",  
        "awayteam" => "setAwayteam",  
        "existingteam"  => "setExistingTeam",
        "published" => "setPublished",  
        "paid" => "setPaid",
        "confnum" => "setConfirmationNumber",  // need to change the object variable
        "confirmed" => "setConfirmed",
        "allstarevent" => "setPlayingInAllStarEvent",  
        "tournament" => "setPlayingInTournament",  
        "regdate" => "setRegistrationDate",  // need to change the object variable
        "registeredby" => "setRegisteredBy",
        "divclass" => "setDivisionClass",
        "requestedclass" => "setRequestedClassification",
        "tosack" => "setTosAck",
        "ipaddr" => "setIpAddress"
    );
    
    /**
     * This function will map an array and return a Game object.
     * {@inheritDoc}
     * @see \cjs\lib\AbstractMapper::map()
     * @return \swibl\Game;
     */
    public function build($result) {
        
        $reg = new Registration();
        
        try {
            $this->map($result, $this->fieldMap,$reg);
        } catch (Exception $e) {
            throw $e;
        }
        
        return $reg;
        
    }

}

?>