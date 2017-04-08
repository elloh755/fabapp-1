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
            <h1 class="page-header">Service History</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <?php /*
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calculator fa-fw"></i> Filters
                </div>
                <div class="panel-body">
                    
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>	*/?>
    <div class="row">
        <!-- /.col-lg-8 -->
        <div class="col-lg-12">
            <div class="panel panel-default">
          <!--  <div class="panel-heading">
                    <i class="fa fa-folder fa-fw"></i> Results
                </div>
                <div class="panel-body" style="max-height: 500px; overflow-y: scroll;"> //TODO this is commented out to test the new output -->
                <div id="results" class="dataTables_wrapper"><div class="dataTables_length" id="example_length">
                	<label>Show <select name="example_length" aria-controls="example" class="">
	                	<option value="10">10</option>
	                	<option value="25">25</option>
	                	<option value="50">50</option>
	                	<option value="100">100</option>
                	</select> entries</label></div>
                	<div id="search" class="dataTables_filter">
                		<label>Search:<input type="search" class="" placeholder="" aria-controls="example"></label>
                	</div>
                	<table class="table table-striped table-bordered" width='100%' border='1' id="history"><tr>
                	<?php 
                	if(isset($_GET['d_id']))
                		$query = "SELECT sc_id, d_id, sl_id, sc_time, sc_notes, solved FROM service_call WHERE d_id = " . $_GET['d_id']. " ORDER BY sc_id ASC";
                	else
                		$query = "SELECT sc_id, staff_id, d_id, sl_id, sc_time, sc_notes, solved FROM service_call ORDER BY sc_id ASC";
                	if ($result = $mysqli->query($query)){
                    	if (mysqli_num_rows($result)>0)
                    	{
                    		//loop thru the field names to print the correct headers
                    		echo "<thead>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Device Name</th>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Service Level</th>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Opened</th>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Reply Count</th>";
	                    		echo "<th style='text-align:center' width=\"" . 100/(mysqli_num_fields($result)+3) . "%\">Solved</th>";
	                    		echo "<th style='text-align:center' width=\"" . 4*(100/(mysqli_num_fields($result)+3)) . "%\">Service Notes</th></tr>";
                    		echo "</thead>";
                    			
                    		//display the data
                    		echo "<body>";
                    		while ($cols = mysqli_fetch_array($result, MYSQLI_ASSOC))
                    		{
                    			for($i = 0; $i <= mysqli_num_fields($result); $i++){
                    				switch($i){
                    					/*case 0:		//first column
                    						echo "<td align='center' style='padding: 2px;'>" . $cols['sc_id'] . "</td>";
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
                    								echo "<td align='center' style='padding: 2px;'>" . $staffName['title'] . "</td>";
                    							}
                    							else
                    								echo "<td align='center' style='padding: 2px;'>Invalid User ID</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 2px;'>Invalid User ID</td>";
                    					break;*/
                    					case 0:		//first column
                    						if($deviceName = $mysqli->query("SELECT device_desc FROM devices WHERE d_id = " . $cols['d_id'])){
                    							$deviceName = mysqli_fetch_array($deviceName);
                    							echo "<td align='center' style='padding: 15px'>" . $deviceName["device_desc"] . "</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 15px'>Invalid Machine ID</td>";
                    					break;
                    					case 1:		//second column
                    						if($serviceLevel = $mysqli->query("SELECT msg FROM service_lvl WHERE sl_id = " . $cols['sl_id'])){
                    							if($serviceLevel->num_rows > 0){
	                    							$serviceLevel = mysqli_fetch_array($serviceLevel, MYSQLI_ASSOC);
    	                							echo "<td align='center' style='padding: 15px'>" . $serviceLevel['msg'] . "</td>";
                    							}
                    							else
                    								echo "<td align='center' style='padding: 2px'>Invalid Service Level</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 15px'>Invalid Service Level</td>";
                    						//<td><i class="fa fa-<?php if ( $service_call->getUser()->getIcon() ) echo  $service_call->getUser()->getIcon(); else echo "user"; fa-fw"></i><td> 
                    					break;
                    					case 2:		//third column
											echo("<td align='center' style='padding: 15px'>" . date('M d g:i a', strtotime($cols["sc_time"])) . "</td>");
                    					break;
                    					case 3: 	//fourth
                    						if($rows = $mysqli->query("SELECT * FROM reply WHERE sc_id = " . $cols['sc_id'])){
                    							$row_cnt = $rows->num_rows;
                    							echo "<td align='center' style='padding: 15px'><a href = '/service/individualHistory.php?service_call_id=".$cols['sc_id']."'>" . $row_cnt . "</td>";
                    						}
                    						else
                    							echo "<td align='center' style='padding: 15px'>There was an error loading the reply count</td>";
                    					break;
                    					case 4: 	//fifth column
                    						switch($cols['solved']){
                    							case 'Y':
                    								echo "<td align='center' style='padding: 15px'>Completed</td>";
                    							break;
                    							case 'N':
                    								echo "<td align='center' style='padding: 15px'>Incomplete</td>";
                    							break;
                    						}
                    					break;
                    					case 5:		//sixth column
                    						echo "<td align='left' style='padding: 15px'>&nbsp; " . $cols['sc_notes'] . "</td>";
                    					break;
                    				}
                    			}
                    			echo "</tr>";
                    		}
                    	}else
                    		echo "<tr><td align='center' style='padding: 6px;'>No Results Found!</td></tr>";
                    }
                    else{
                    	echo "<tr><td align='center' style='padding: 6px;'>No Results Found!</td></tr>";
                    }
                    echo "</body>";?>
                    </table>
                   <!-- </div> -->
            </div>
        </div>
        <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
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