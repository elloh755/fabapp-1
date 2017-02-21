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
            <h1 class="page-header">Report Issue</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-ticket fa-fw"></i> New Ticket
                </div>
                <div class="panel-body">
                   	<table class="table table-striped">
                   		<tr><td>Device Group</td><td>
							<select class="form-control">
                    			<?php
		                        	/* check connection */
                    			if ($mysqli->connect_errno){
                    					echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
                        		        exit();
                            		}
                            		if (!$result = $mysqli->query("SELECT dg_id,dg_name FROM device_group ORDER BY dg_name ASC")) {
                            			echo "Table select failed: (" . $mysqli->errno . ") " . $mysqli->error;
                            		}
                            		while ($rows = mysqli_fetch_array($result)) {
		                            	echo "<option value=" .$rows['dg_id']. ">" .$rows['dg_name']. "</option>";
        		                    }?> 
                				</select>
                   		</td>
		    	        <tr><td>Machine ID</td>
		    	        <td><div class="form-group">
							<textarea class="form-control" rows="1" id="machineID" style="resize:none"></textarea>
						</div></td>
        	    		<tr><td>Service Level</td>
        	    		<td><label class="radio-inline"><input type="radio" name="optradio">Issue</label>
							<label class="radio-inline"><input type="radio" name="optradio">Maintenance</label>
							<label class="radio-inline"><input type="radio" name="optradio">Non-Operable</label></td>
			            <tr><td>Notes:</td>
			            <td><div class="form-group">
							<textarea class="form-control" rows="5" id="notes" style="resize:none"></textarea>
						</div></td>
	            		<tr><td>Staff ID</td><td><?php echo $staff->getOperator();?></td>
	            		<tr><td>Current Date</td><td><?php echo $date = date("m/d/Y h:i a", time());?></td>
	            		<tr><td><input class="btn btn-primary pull-right" type="reset" value="Reset"></td><td><input class="btn btn-primary" type="submit" value="Submit"></td>
          			</table>
                </div>
            </div>
        </div>
        <!-- /.col-lg-8 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php
//Standard call for dependencies
include_once ($_SERVER['DOCUMENT_ROOT'].'/pages/footer.php');
?>