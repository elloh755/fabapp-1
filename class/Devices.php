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


class Devices {
    private $d_id;
    private $device_id;
    private $public_view;
    private $device_desc;
    private $d_duration;
    private $base_price;
    private $dg_id;
    private $url;
    private $device_key;
    
    public function __construct($d_id) {
        global $mysqli;
        $this->d_id = $d_id;
        
        
        if ($result = $mysqli->query("
             SELECT *
             FROM `Devices`
             WHERE `d_id` = '$d_id';
        ")){
            $row = $result->fetch_assoc();
            
            $this->setDevice_id($row['device_id']);
            $this->setDevice_desc($row['device_desc']);
            $this->setPublic_view($row['public_view']);
            $this->setD_duration($row['d_duration']);
            $this->setBase_price($row['base_price']);
            $this->setDg_id($row['dg_id']);
            $this->setUrl($row['url']);
            $this->setDevice_key($row['device_key']);
            $result->close();
        } else
            throw new Exception("Invalid Device ID");
    }
    
    public static function is_open($d_id){
        global $mysqli;
        
        if($result = $mysqli->query("
            SELECT * 
            FROM `transactions`
            WHERE d_id = '$d_id' AND `status_id` < 12
        ")){
            if ($result->num_rows > 0)
                return true;
            return false;
        }
        return false;
    }

    public static function regexDID($d_id){
		global $mysqli;
        if (preg_match("/^\d+$/", $d_id) == 0){
            echo "Invalid D ID.";
            return false;
        }
        
        //Check to see if device exists
        if ($result = $mysqli->query("
            SELECT *
            FROM `devices`
            WHERE `d_id` = '$d_id'
            LIMIT 1;
        ")){
            if ($result->num_rows == 1)
                return true;
            return false;
        } else {
            return false;
        }
    }
    
    public static function regexDeviceID($device_id){
        if (preg_match("/^\d{4}$/", $d_id) == 0){
            echo "Invalid Device ID. ";
            return false;
            
        }//Check to see if device exists
        if ($result = $mysqli->query("
            SELECT *
            FROM `devices`
            WHERE `device_id` = '$device_id'
            LIMIT 1;
        ")){
            if ($result->num_rows == 1)
                return true;
            return false;
        } else {
            return false;
        }
    }
    
    public function getD_id() {
        return $this->d_id;
    }

    public function getDevice_id() {
        return $this->device_id;
    }

    public function getPublic_view() {
        return $this->public_view;
    }

    public function getD_duration() {
        return $this->d_duration;
    }

    public function getBase_price() {
        return $this->base_price;
    }

    public function getDg_id() {
        return $this->dg_id;
    }
    
    public function getDg_Name(){
        global $mysqli;
        
        if($result = $mysqli->query("
            SELECT `dg_name`
            FROM `device_group`
            WHERE `dg_id` = '".$this->dg_id."'
            LIMIT 1;
        ")){
            $row = $result->fetch_assoc();
            return $row['dg_name'];
        }
        return "Not Found";
    }
    
    public function getDg_Parent(){
        global $mysqli;
        
        if($result = $mysqli->query("
            SELECT `dg_parent`
            FROM `device_group`
            WHERE `dg_id` = '".$this->dg_id."'
            LIMIT 1;
        ")){
            $row = $result->fetch_assoc();
            return $row['dg_parent'];
        }
        return false;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getDevice_key() {
        return $this->device_key;
    }
    
    public function getDevice_desc() {
        return $this->device_desc;
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
    			echo "<td><a href = 'http://fabapp:8080/service/ticketHistory.php?d_id=".$d_id."'><i class='fa fa-circle' style='color:".$color."'></i>    " . $device_desc . "</a></td>"; //TODO add space to print statement
    		else echo "<td><i class='fa fa-circle' style='color:".$color."'></i>" . "\t" . $device_desc . "</td>";
    	}
    	else{
    		echo "<td><i class='fa fa-circle' style='color:".$color."'></i>" . "\t" . $device_desc . "</td>";
    	}
    }
    
}
