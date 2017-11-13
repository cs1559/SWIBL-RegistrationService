<?php
namespace swibl\services\registration;

use swibl\core\BaseObject;

class Registration extends BaseObject {
    
    var $id = null;
    var $divisionid = null;
    var $divisionname= null;
    var $teamid = null;
    var $teamname = null;
    var $seasonid = null;
    var $seasontitle = null;
    var $published = 0;
    var $name = null;
    var $phone = null;
    var $cellphone = null;
    var $address = null;
    var $state = null;
    var $agegroup = null;
    var $existingteam = null;
    var $email = null;
    var $city = null;
    
    // Added 07.31.2011
    var $paid = null;
    var $confirmed = false;
    var $tournament = 0;
    var $allstarevent = 0;
    var $registrationdate = null;
    var $registeredby = null;
    var $confirmationnumber = null;
    var $divisionclass = null;
    var $cost = null;
    var $ipaddr = null;
    var $tosack = null;
    var $requestedclass = null;
    
    function __construct() {
        parent::__construct();
        $this->id = 0;
    }
    
    function setId($inParm) {
        $this->id = $inParm;
    }
    function getId() {
        return $this->id;
    }
    function setDivisionId($did) {
        $this->divisionid = $did;
    }
    function getDivisionId() {
        return $this->divisionid;
    }
    function setSeasonId($sid) {
        $this->seasonid = $sid;
    }
    function getSeasonId() {
        return $this->seasonid;
    }
    function setTeamId($tid) {
        $this->teamid = $tid;
    }
    function getTeamId() {
        return $this->teamid;
    }
    
    function setTeamName($name) {
        $this->teamname = $name;
    }
    function getTeamName() {
        return $this->teamname;
    }
    function setDivisionName($name) {
        $this->divisionname = $name;
    }
    function getDivisionName() {
        if ($this->divisionname == null || strlen($this->divisionname) == 0) {
            return "Unassigned";
        }
        return $this->divisionname;
    }
    function setSeasonTitle($title) {
        $this->seasontitle = $title;
    }
    function getSeasonTitle() {
        return $this->seasontitle;
    }
    function setPublished($pub) {
        $this->published = $pub;
    }
    function getPublished() {
        return $this->published;
    }
    function setName($name) {
        $this->name = $name;
    }
    function getName() {
        return $this->name;
    }
    function setPhone($phone) {
        $this->phone = $phone;
    }
    function getPhone() {
        return $this->phone;
    }
    function setCellPhone($cellphone) {
        $this->cellphone = $cellphone;
    }
    function getCellPhone() {
        return $this->cellphone;
    }
    function setAddress($address) {
        $this->address = $address;
    }
    function getAddress() {
        return $this->address;
    }
    function setCity($city) {
        $this->city = $city;
    }
    function getCity() {
        return $this->city;
    }
    function setState($state) {
        $this->state = $state;
    }
    function getState() {
        return $this->state;
    }
    
    function setAgeGroup($ag) {
        $this->agegroup = $ag;
    }
    function getAgeGroup() {
        return $this->agegroup;
    }
    function setEmail($email) {
        $this->email = $email;
    }
    function getEmail() {
        return $this->email;
    }
    function setExistingTeam($ind) {
        $this->existingteam = $ind;
    }
    function getExistingTeam() {
        return $this->existingteam;
    }
    
    function setPaid($paid = false) {
        $this->paid = $paid;
    }
    function isPaid() {
        return $this->paid;
    }
    function setConfirmed($confirmed = false) {
        $this->confirmed = $confirmed;
    }
    function isConfirmed() {
        return $this->confirmed;
    }
    
    function setRegistrationDate($date) {
        $this->registrationdate = $date;
    }
    function getRegistrationDate() {
        return $this->registrationdate;
    }
    function setRegisteredBy($name) {
        $this->registeredby = $name	;
    }
    function getRegisteredBy() {
        return $this->registeredby;
    }
    function setConfirmationNumber($num) {
        $this->confirmationnumber = $num;
    }
    function getConfirmationNumber() {
        return $this->confirmationnumber;
    }
    function setPlayingInTournament($flag) {
        $this->tournament = $flag;
    }
    function isPlayingInTournament() {
        return $this->tournament;
    }
    function setPlayingInAllStarEvent($flag) {
        $this->allstarevent = $flag;
    }
    function isPlayingInAllStarEvent() {
        return $this->allstarevent;
    }
    function getDivisionClass() {
        return $this->divisionclass;
    }
    function setDivisionClass($divclass) {
        $this->divisionclass = $divclass;
    }
    function getCost() {
        return $this->cost;
    }
    function setCost($cost) {
        $this->cost = $cost;
    }
    function getIpAddress() {
        return $this->ipaddr;
    }
    function setIpAddress($ip) {
        $this->ipaddr = $ip;
    }
    function setTosAck($ack) {
        $this->tosack = $ack;
    }
    function getTosAck() {
        return $this->tosack;
    }
    function setRequestedClassification($class) {
        $this->requestedclass = $class;
    }
    function getRequestedClassification() {
        return $this->requestedclass;
    }
    
}