<?php
/*
 *   CC BY-NC-AS UTA FabLab 2016-2017
 *   FabApp V 0.9
 */
include_once ($_SERVER['DOCUMENT_ROOT'].'/class/Users.php');

///////////////////////////////////////////////////////////////
//
//   Gatekeeper
//   Checks to see if ID is authorized to use a FabLab device
//

function gatekeeper ($operator, $device_id) {
    global $mysqli;
	

    // Check to see if ID is a 10-digit number
    if (!Users::regexUser($operator)) {
        return array ("status_id" => 1, "ERROR" => "Bad ID", "authorized" => "N");
    }
    
    // Check to see if device ID is a valid 4-digit number
    if (preg_match("/^\d{4}$/",$device_id) == 0) {
        return array ("status_id" => 1, "ERROR" => "Invalid or missing device id value - $device_id", "authorized" => "N");
    }
    
    //Prevent new Ticket if previous ticket has not been closed
    if( $result = $mysqli->query("
        SELECT * 
        FROM `transactions`
        LEFT JOIN devices
        ON devices.d_id = transactions.d_id
        WHERE device_id = $device_id AND status_id < 12
        LIMIT 1;")){
        if( $result->num_rows > 0){
            return array ("status_id" => 1, "ERROR" => "Can not start new print on this printer until previous ticket has been closed.", "authorized" => "N");
        }
    } else {
        return array ("status_id" => 0, "ERROR" => $mysqli->error);
    }
    
    
    //Check to see machine's service status
    if($status = $mysqli->query("SELECT * FROM `service_call` WHERE d_id = " . $device_id . " AND solved = 'N' ORDER BY sc_time DESC")){
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
    $role = $mysqli->query("SELECT r_id FROM `users` WHERE operator = " . $operator);
	if($color == "red"){
		switch($role){
			case 7:
			case 10:
				return array ("status_id" => 10, "authorized" => "Y");
			default:
				return array ("status_id" => 1, "ERROR" => "Maintenance required.", "authorized" => "N");
			break;
		}
	}
	elseif($color == "yellow"){
		switch($role){
			case 7:
			case 10:
				return array ("status_id" => 10, "authorized" => "Y");
			default:
				return array ("status_id" => 1, "ERROR" => "Maintenance required.", "authorized" => "N");
			break;
		}
	}
    
    //Deny if operator has unpaid balance or objects in storage
    
    //if membership < date()
	
    // Check to see if device has training modules
    if ($results = $mysqli->query("
        SELECT *
        FROM trainingmodule
        LEFT JOIN devices
        ON trainingmodule.d_id = devices.d_id
        where devices.device_id = '$device_id' AND tm_required = 'Y'
        UNION
        SELECT *
        FROM trainingmodule
        LEFT JOIN devices
        ON trainingmodule.dg_id = devices.dg_id
        where devices.device_id = '$device_id' AND tm_required = 'Y'
    ")){
        while( $row = $results->fetch_assoc() ) {
            $training[] = $row["tm_id"];
        }
	$results->close();
    } else {
        return array ("status_id" => 0, "ERROR" => $mysqli->error);
    }

   
    if ( count($training) == 0) {
        // If device has no training modules, no need to check further
        // so exit the function.
        //echo "No Training Required";
        return array ("status_id" => 10, "authorized" => "Y");
    }

    // Check if user has completed the necessary training modules
    $count = 0;
    foreach($training as $t){
        if ($results = $mysqli->query("
            SELECT *
            FROM `tm_enroll`
            WHERE tm_id = $t AND operator = $operator
        ")){
            $count++;
            $results->close();
        } else {
            return array ("status_id" => 0, "ERROR" => $mysqli->error);
        }
    }
	
    if ($count != count($training)){
        //echo "Device: ".$device_id." requires training"."<br />";
        //echo "Enroll - ". $count ." Class - ". count($training) ."<br />";
        return array ("status_id" => 2, "ERROR" => "Device: $device_id requires training", "authorized" => "N");
    } else {
        //echo "authorized";
        return array ("status_id" => 10, "authorized" => "Y");
    }
}
?>