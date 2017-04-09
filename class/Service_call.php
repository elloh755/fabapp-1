<?php
/*
 *   CC BY-NC-AS UTA FabLab 2016-2017
 *   FabApp V 0.9
 */

/**
 * Description of Devices
 *
 * @author Jon Le
 */


class Service_call {
    private $sc_id;
    private $d_id;
    private $staff_id;
    private $sl_id;
    private $solved;
    private $sc_notes;
    private $sc_time;
    private $user;
    
    public function __construct($sc_id) {
        global $mysqli;
        $this->sc_id = $sc_id;
        
        if ($result = $mysqli->query("
             SELECT *
             FROM `service_call`
             WHERE `sc_id` = '$sc_id';
        ")){
            $row = $result->fetch_assoc();
            
            $this->setD_id($row['d_id']);
            $this->setStaff_id($row['staff_id']);
            $this->setSl_id($row['sl_id']);
            $this->setSolved($row['solved']);
            $this->setNotes($row['sc_notes']);
            $this->setTime($row['sc_time']);
            $this->setUser($row['staff_id']);
            $result->close();
        } else
            throw new Exception("Invalid Service Call ID");
    }
    
    public static function is_solved($sc_id){
        global $mysqli;
        
        if($result = $mysqli->query("
            SELECT solved
            FROM `service_call`
            WHERE sc_id = '$sc_id'
        ")){
            if ($result->fetch_assoc()->solved == 'Y')
                return true;
            return false;
        }
        return false;
    }

    public function getSc_id() {
        return $this->d_id;
    }

    public function getD_id() {
        return $this->d_id;
    }

    public function getStaff_id() {
        return $this->staff_id;
    }

    public function getSl_id() {
        return $this->sl_id;
    }

    public function getSolved() {
        return $this->solved;
    }

    public function getNotes() {
        return $this->sc_notes;
    }
    
    public function getTime() {
    	return $this->sc_time;
    }
    
    public function getDevice_desc(){
        global $mysqli;
        
        if($result = $mysqli->query("
            SELECT device_desc
            FROM `devices`
            WHERE d_id = ".$this->d_id."
            LIMIT 1;
        ")){
            $row = $result->fetch_assoc();
            return $row['device_desc'];
        }
        return "Not Found";
    }
    
    public function getService_lvl(){
        global $mysqli;
        
        if($result = $mysqli->query("
            SELECT msg
            FROM service_lvl
            WHERE sl_id = " . $this->sl_id . "
            LIMIT 1;
        ")){
            $row = $result->fetch_assoc();
            return $row['msg'];
        }
        return false;
    }

    public function getUser() {
        return $this->user;
    }

    public function setD_id($d_id) {
        if (preg_match("/^\d+$/",$device_id) == 0)
            return false;
        $this->d_id = $d_id;
    }

    public function setD_duration($d_duration) {
        if(Transactions::regexTime($d_duration)){
            $this->d_duration = $d_duration;
        } else {
            echo ("Invalid time $d_duration. ");
            return false;
        }
    }
    
    public function setDevice_desc($device_desc) {
        $this->device_desc = $device_desc;
    }

    public function setDevice_id($device_id) {
        if (preg_match("/^\d{4}$/",$device_id)){
            $this->device_id = $device_id;
        } else {
            echo("Invalid Device ID - $device_id. ");
            return false;
        }
    }

    public function setPublic_view($public_view) {
        if (preg_match("/^[YN]{1}$/",$public_view))
            $this->public_view = $public_view;
        else 
            echo "Invalid Public View";
    }

    public function setBase_price($base_price) {
        $this->base_price = $base_price;
    }

    public function setDg_id($dg_id) {
        $this->dg_id = $dg_id;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setDevice_key($device_key) {
        $this->device_key = $device_key;
    }
    
    public static function printDot($staff,$d_id,$device_desc){
    	global $mysqli;
    	//look up current device status
    	$dot = 0;
    	$color = "white";
    	$lookup = "SELECT * FROM `service_call` WHERE d_id = " . $d_id . " AND solved = 'N' ORDER BY sc_time DESC";
    	if($status = $mysqli->query($lookup)){
    		while ($ticket = $status->fetch_assoc()){
    			if($ticket['sl_id'] > $dot)
    				$dot = $ticket['sl_id'];
    		}
    		if($status == NULL || $dot == 0)
    			$color = "green";
    		elseif($dot < 7)
    			$color = "yellow";
    		else
    			$color = "red";
    	}
    	if($staff){
    		if($staff->getRoleID() > 7)
    			echo "<td><a href = '/service/ticketHistory.php?device_id=".$d_id."'><i class='fa fa-circle fa-fw' style='color:".$color."'></i>&nbsp; " . $device_desc . "</a></td>";
    		else
    			echo "<td><i class='fa fa-circle fa-fw' style='color:".$color."'></i>&nbsp; " . $device_desc . "</td>";
    	}
    	else{
    		echo "<td><i class='fa fa-circle fa-fw' style='color:".$color."'></i>&nbsp; " . $device_desc . "</td>";
    	}
    }
    
}
