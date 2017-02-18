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
            <h1 class="page-header">Page Name</h1>
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
                   			<div class="dropdown">
	  							<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    DEVICE GROUP
							  	</button>
								<ul class="dropdown-menu scrollable-menu" role="menu" aria-labelledby="dropdownMenuButton">
							    	<li><a class="dropdown-item" href="#">3D</a></li>
	    							<li><a class="dropdown-item" href="#">POLY</a></li>
							    	<li><a class="dropdown-item" href="#">SHOP</a></li>
							    	<li><a class="dropdown-item" href="#">LASER</a></li>
	    							<li><a class="dropdown-item" href="#">VINYL</a></li>
								    <li><a class="dropdown-item" href="#">E_STATION</a></li>
							    	<li><a class="dropdown-item" href="#">UPRINT</a></li>
		    						<li><a class="dropdown-item" href="#">DELTA</a></li>
								    <li><a class="dropdown-item" href="#">SCAN</a></li>
								    <li><a class="dropdown-item" href="#">SEW</a></li>
		    						<li><a class="dropdown-item" href="#">KILN</a></li>
								    <li><a class="dropdown-item" href="#">MILL</a></li>
								    <li><a class="dropdown-item" href="#">VR</a></li>
	    							<li><a class="dropdown-item" href="#">AIR_BRUSH</a></li>
								    <li><a class="dropdown-item" href="#">NFPRINTER</a></li>
								    <li><a class="dropdown-item" href="#">EMBROIDERY</a></li>
	    							<li><a class="dropdown-item" href="#">SCREEN</a></li>
	    							<!-- these are purely temporary. Will be based on tables later -->
	    						</ul>
							</div>
                   		</td>
		    	        <tr><td>Machine ID</td>
		    	        <td><div class="form-group">
							<textarea class="form-control" rows="1" id="machineID"></textarea>
						</div></td>
        	    		<tr><td>Service Level</td><td>
        	    			<label class="radio-inline"><input type="radio" name="optradio">Issue</label>
							<label class="radio-inline"><input type="radio" name="optradio">Maintenance</label>
							<label class="radio-inline"><input type="radio" name="optradio">Non-Operable</label></td>
			            <tr><td>Notes:</td>
			            <td><div class="form-group">
							<textarea class="form-control" rows="5" id="notes"></textarea>
						</div></td>
	            		<tr><td>Staff ID</td>
		    	        <td><div class="form-group">
							<textarea class="form-control" rows="1" id="StaffID"></textarea>
						</div></td>
	            		<tr><td>Current Date</td><td>Temperature</td>
	            		<tr><td><input class="btn btn-primary pull-right" type="reset" value="Reset"></td><td><input class="btn btn-primary" type="submit" value="Submit"></td>
	            		<tr>
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