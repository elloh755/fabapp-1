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
            <h1 class="page-header">Open Tickets</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- <div class="panel-heading">
                    <i class="fa fa-calendar fa-fw"></i> Open Tickets
                </div>
                <div class="panel-body" style="max-height: 500px; overflow-y: scroll;"> -->
                	<table class="table table-striped table-bordered" border='1' id="history"><tr>
                    <?php 
                    if ($result = $mysqli->query("
                    SELECT sc_id, staff_id, d_id, sl_id, sc_time, sc_notes
                    FROM service_call
                    WHERE solved = 'N'
                    ORDER BY sc_id ASC")){
                    	if (mysqli_num_rows($result)>0)
                    	{
                    		//loop thru the field names to print the correct headers
                    		echo "<thead>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Device Name</th>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Staff Level</th>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Opened</th>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Reply Count</th>";
	                    		echo "<th style='text-align:center' width=\"" . 4*(100/(mysqli_num_fields($result)+3)) . "%\">Service Notes</th></tr>";
	                    	echo "</thead>";
                    			
                    		//display the data
                    		while ($cols = mysqli_fetch_array($result, MYSQLI_ASSOC))
                    		{
                    			echo "<tr onclick=\"document.location.href='/service/individualHistory.php?service_call_id=".$cols['sc_id']."'\">";
                    			for($i = 0; $i <= mysqli_num_fields($result); $i++){
                    				switch($i){
                    					case 0:		//Device Name
                    						if($deviceName = $mysqli->query("SELECT device_desc FROM devices WHERE d_id = " . $cols['d_id'])){
                    							$deviceName = mysqli_fetch_array($deviceName);
                    							echo "<td align='center' style='padding: 15px'>" . $deviceName["device_desc"] . "</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 15px'>Invalid Machine ID</td>";
                    					break;
                    					case 1:		//Staff Level
                    						if($staffIcon = $mysqli->query("
											    SELECT icon
											    FROM users
											    WHERE operator = " . $cols['staff_id'])){
												if($staffIcon->num_rows > 0){
													$staffIcon = mysqli_fetch_array($staffIcon, MYSQLI_ASSOC);
													echo "<td align='center' style='padding: 15px'><i class='fa fa-" . $staffIcon['icon'] . " fa-lg fa-fw'></i></td>";
												}
												else
													echo "<td align='center' style='padding: 2px;'>Invalid User ID</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 2px;'>Invalid User ID</td>";
                    					break;
                    					case 2:		//Opened
                    						echo("<td align='center' style='padding: 15px'>" . date('M d g:i a', strtotime($cols["sc_time"])) . "</td>");
                    					break;
                    					case 3:		//Reply Count
                    						if($rows = $mysqli->query("SELECT * FROM reply WHERE sc_id = " . $cols['sc_id'])){
                    							$row_cnt = $rows->num_rows;
                    							echo "<td align='center' style='padding: 15px'>" . $row_cnt . "</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 15px'>There was an error loading the reply count</td>";
                    					break;
                    					case 4:		//Service Notes
                    						if($serviceLevel = $mysqli->query("SELECT msg FROM service_lvl WHERE sl_id = " . $cols['sl_id'])){
                    							if($serviceLevel->num_rows > 0){
                    								$serviceLevel = mysqli_fetch_array($serviceLevel, MYSQLI_ASSOC);
                    								echo "<td align='center' style='padding: 15px'><strong>" . $serviceLevel['msg'] . "</strong> - " . $cols['sc_notes'] . "</td>";
                    							}
                    							else
                    								echo "<td align='center' style='padding: 15px'>Invalid Service Level</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 15px'>Invalid Service Level</td>";
                    					break;
                    				}
                    			}
                    			echo "</tr>";
                    		}
                    	}else
                    		echo "<tr><td align='center' style='padding: 6px'>No Results found!</td></tr>";
                    	
                    }
                    else{
                    	echo "<tr><td align='center' style='padding: 6px'>No Results found!</td></tr>";
                    } ?>
                    </table>
                   <!-- </div> -->
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
	$(document).ready(function() {
	    $('#history').DataTable();
	} );
</script>
<?php
//Standard call for dependencies
include_once ($_SERVER['DOCUMENT_ROOT'].'/pages/footer.php');
?>