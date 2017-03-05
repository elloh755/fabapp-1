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
                    <i class="fa fa-calendar fa-fw"></i> History
                </div>
                <div class="panel-body" style="max-height: 500px; overflow-y: scroll;">
                    <?php 
                    if ($result = $mysqli->query("
                    SELECT sc_id, staff_id, d_id, sl_id, sc_time, sc_notes
                    FROM service_call
                    WHERE solved = 'N'
                    ORDER BY sc_time DESC")){
                    	echo "<table width='100%' border='1' class='Tabella'><tr>";
                    	if (mysqli_num_rows($result)>0)
                    	{
                    		//loop thru the field names to print the correct headers
                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Service Call ID</th>";
                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Staff Level</th>";
                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Device Name</th>";
                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Service Level</th>";
                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Ticket Open Date</th>";
                    		echo "<th style='text-align:center' width=\"" . 4*(100/(mysqli_num_fields($result)+3)) . "%\">Service Notes</th>";
                    		echo "</tr>";
                    			
                    		//display the data
                    		while ($cols = mysqli_fetch_array($result, MYSQLI_ASSOC))
                    		{
                    			echo "<tr onclick=\"document.location.href = '/service/individualHistory.php?id=".$cols['sc_id']."&machineID=".$cols['d_id']."'\">";
                    			
                    			for($i = 0; $i < mysqli_num_fields($result); $i++){
                    				switch($i){
                    					case 0:		//first column
                    						echo "<td align='left'>" . $cols['sc_id'] . "</td>";
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
												    WHERE operator = " . $cols['staff_id'] . "
												);")){
                    							if($staffName->num_rows > 0){
                    								$staffName = mysqli_fetch_array($staffName, MYSQLI_ASSOC);
                    								echo "<td align='left'>" . $staffName['title'] . "</td>";
                    							}
                    							else
                    								echo "<td align='left'>Invalid User ID</td>";
                    						}
                    						else
                    							echo "<td align='left'>Invalid User ID</td>";
                    					break;
                    					case 2:		//third column
                    						if($deviceName = $mysqli->query("SELECT device_desc FROM devices WHERE d_id = " . $cols['d_id'])){
                    							echo "<td align='left'>" . $deviceName->fetch_object()->device_desc . "</td>";
                    						}
                    						else
                    							echo "<td align='left'>Invalid Machine ID</td>";
                    					break;
                    					case 3:		//fourth column
                    						if($serviceLevel = $mysqli->query("SELECT msg FROM service_lvl WHERE sl_id = " . $cols['sl_id'])){
                    							if($serviceLevel->num_rows > 0){
	                    							$serviceLevel = mysqli_fetch_array($serviceLevel, MYSQLI_ASSOC);
    	                							echo "<td align='left'>" . $serviceLevel['msg'] . "</td>";
                    							}
                    							else
                    								echo "<td align='left'>Invalid Service Level</td>";
                    						}
                    						else
                    							echo "<td align='left'>Invalid Service Level</td>";
                    					break;
                    					case 4:		//fifth column
                    						echo "<td align='left'>" . $cols['sc_time'] . "</td>";
                    					break;
                    					case 5:		//sixth column
                    						echo "<td align='left'>" . $cols['sc_notes'] . "</td>";
                    					break;
                    				}
                    			}
                    			/*
                    			foreach ($cols as $data)
                    			{
                    				echo "<td align='left'>" . $data . "</td>";
                    			}*/
                    			echo "</tr>";
                    		}
                    	}else{
                    		echo "<tr><td>No Results found!</td></tr>";
                    	}
                     	echo "</table>";
                    }
                    else{
                    	echo "<tr><td>No Results found!</td></tr>";
                    } ?>
                   </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>
</div>
<!-- /#page-wrapper -->
<?php
//Standard call for dependencies
include_once ($_SERVER['DOCUMENT_ROOT'].'/pages/footer.php');
?>