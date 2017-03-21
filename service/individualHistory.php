<?php
/*
 *   CC BY-NC-AS UTA FabLab 2016-2017
 *   FabApp V 0.9
 */
include_once ($_SERVER['DOCUMENT_ROOT'].'/pages/header.php');
?>
<title><?php echo $sv['site_name'];?> Admin Base</title>
<?php if ($staff) if($staff->getRoleID() < 7){
    //Not Authorized to see this Page
    header('Location: /index.php');
}?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Service Replies</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calendar-check-o fa-fw"></i> Replies
                </div>
                <div class="panel-body" style="max-height: 150px; overflow-y: scroll;">
                    <?php 
                    if ($result = $mysqli->query("
                    SELECT reply.sr_id, reply.staff_id, reply.sr_time, reply.sr_notes, service_call.d_id FROM reply LEFT JOIN service_call
					ON (reply.sc_id=service_call.sc_id)
					WHERE service_call.sc_id = " . $_GET['service_call_id'] . "
					ORDER BY reply.sr_time DESC")){
                    	echo "<table width='100%' border='1'><tr>";
                    	if (mysqli_num_rows($result)>0)
                    	{
                    		//loop thru the field names to print the correct headers
                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Service Reply ID</th>";
                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Staff Level</th>";
                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Service Reply Date</th>";
                    		echo "<th style='text-align:center' width=\"" . 4*(100/(mysqli_num_fields($result)+3)) . "%\">Service Reply Notes</th>";
                    		echo "</tr>";
                    			
                    		//display the data
                    		
                    		while ($cols = mysqli_fetch_array($result,MYSQLI_ASSOC))
                    		{
                    			for($i = 0; $i < mysqli_num_fields($result); $i++){
                    				switch($i){
                    					case 0:		//first column
                    						echo "<td align='center' style='padding: 2px;'>" . $cols['sr_id'] . "</td>";
                    					break;
                    					case 1:		//second column
                    						if($staffName = $mysqli->query("
                    							SELECT title
												FROM role
												AS title
												WHERE r_id = (
												    SELECT r_id
												    FROM users
												    AS r_id
												    WHERE operator = " . $cols['staff_id'] . ");")){
                    							if($staffName->num_rows > 0){
                    								$staffName = mysqli_fetch_array($staffName, MYSQLI_ASSOC);
                    								echo "<td align='center' style='padding: 2px;'>" . $staffName['title'] . "</td>";
                    							}
                    							else
                    								echo "<td align='center' style='padding: 2px;'>Invalid User ID</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 2px;'>Invalid User ID</td>";
                    					break;
                    					case 2:		//third column
                    						echo "<td align='center' style='padding: 2px;'>" . $cols['sr_time'] . "</td>";
                   						break;
                    					case 3:		//fourth column
                    						echo "<td align='left' style='padding: 10px;'>" . $cols['sr_notes'] . "</td>";
                   						break;
                    				}
                    			}
                    			echo "</tr>";
                    		}
                    	}else{
                    		echo "<tr><td align = 'center'>No history to display!</td></tr>";
                    	}
                    	echo "</table>";
                    }
                    else{
                    	echo "<tr><td align = 'center'>No history to display!</td></tr>";
                	} ?>
                   </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
        <div class="alert alert-danger" role = "alert" id="errordiv" style="display:none;"><p id="errormessage"></p></div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-ticket fa-fw"></i> Update Ticket
                </div>
                <div class="panel-body">
                   	<form name="reply" method= "POST" action="/service/insertReply.php" onsubmit="return validateForm();">
				<table class="table table-striped">
				<tr><td>Service Call Number:</td>
					<td><?php echo "<input type='text' name='service_call_number' value=" . $_GET['service_call_id'] . " readonly>"?></td></tr>
				<tr><td>Device Name:</td>
					<td>
                    <?php	/* check connection */
						/*
						*	dynamic dropdown
						*	default (first) = current selection
						*	list contains all devices in current group
						*/
                    	echo "<select class='form-control' name='dev'>";
                    	$default_value = "SELECT device_desc,d_id,dg_id FROM devices AS d_id WHERE d_id = (SELECT d_id FROM service_call AS d_id WHERE sc_id = ". $_GET['service_call_id'] . ")";
						if ($default = $mysqli->query($default_value)){
							$default = mysqli_fetch_array($default);
							$list_elements = "SELECT d_id,device_desc FROM devices WHERE dg_id = " . $default['dg_id'] . " ORDER BY device_desc ASC";
							echo "<option selected value=" . $default['d_id'] . ">" . $default['device_desc'] . "</option>";
							if($list = $mysqli->query($list_elements)){
								while ($rows = mysqli_fetch_array($list,MYSQLI_ASSOC)) {
									if($rows['d_id'] == $default['d_id'])
										continue;
									else
										echo "<option value=" . $rows['d_id'] . ">" . $rows['device_desc'] . "</option>";
								}
							}
							else
								echo "<option value=0>Error Loading Device Group</option>";
							echo "</select>";
							
						}
						else
							echo "There was an error loading device description";						
						?> 
						</td></tr>
					<tr>
						<td>Service Level</td>
						<td><?php
						if($options = $mysqli->query("SELECT sl_id,msg FROM service_lvl")){
							if($status = $mysqli->query("SELECT sl_id FROM service_call WHERE sc_id = " . $_GET['service_call_id'])){
								$status = mysqli_fetch_array($status);
								while($row = $options->fetch_array(MYSQLI_ASSOC)){
									echo "<label class='radio-inline'><input type='radio' name='service_level' value='" . $row['sl_id'] . "'" . (($status['sl_id'] == $row['sl_id']) ? "checked='checked'" : "") . ">" . $row['msg'] . "</label>";
								}
								echo "<label class='radio-inline'><input type='radio' name='service_level' value='100'>Completed</label></td></tr>";
							}
							else
								echo "<td align = 'center'>Error loading Service Call</td>";
						}
						else
							echo "<td align = 'center'>Error loading Service Levels</td>";
						?>
						<tr>
							<td>Notes:</td>
							<td><div class="form-group">
							<textarea class="form-control" rows="5" name="notes" style="resize: none"></textarea>
							</div></td></tr>
						<tr>
							<td>Staff ID</td>
							<td><?php echo $staff->getOperator();?></td></tr>
						<tr>
							<td>Current Date</td>
							<td><?php echo $date = date("m/d/Y h:i a", time());?></td>
						
						</tr>
						<tr>
							<td><input class="btn btn-primary pull-right" type="reset" value="Reset"></td>
							<td><input class="btn btn-primary" type="submit" value="Submit"></td>
						</tr>
					</table>
				</form>
                </div>
            </div>
        </div>
        <!-- /.col-lg-8 -->
    </div>
</div>
<!-- /#page-wrapper -->

<script type="text/javascript">
	function validateRadio(radios){
		for(i = 0; i< radios.length ; ++i)
			if(radios[i].checked)
				return true;
		return false;
	}
	function validateForm(){
		var dev = document.getElementById("dev").value;
		var radiocheck = false;
		if(validateRadio(document.forms["reply"]["service_level"]))
			radiocheck = true;
		var notes = document.getElementById("notes").value;
		if(dev == "" ||  notes =="" || radiocheck == false){
			document.getElementById('errordiv').style.display = 'block';
			document.getElementById("errormessage").innerHTML = "All fields are required";			
			return false;
		}
	}
</script>
<?php
//Standard call for dependencies
include_once ($_SERVER['DOCUMENT_ROOT'].'/pages/footer.php');
?>