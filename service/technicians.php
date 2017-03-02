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
                    <i class="fa fa-calculator fa-fw"></i> Tickets
                </div>
                <div class="panel-body" style="max-height: 500px; overflow-y: scroll;">
                    <?php 
                    if ($result = $mysqli->query("
                    SELECT sc_id, staff_id, d_id, sl_id, sc_time, solved, sc_notes
                    FROM service_call
                    WHERE solved = 'N'
                    ORDER BY sc_time DESC")){
                    	echo "<table width='100%' border='1' class='Tabella'><tr>";
                    	if (mysqli_num_rows($result)>0)
                    	{
                    		//loop thru the field names to print the correct headers
                    		$i = 0;
                    		while ($i < mysqli_num_fields($result))
                    		{
                    			echo "<th style='text-align:center' width=\"" . 100/mysqli_num_fields($result) . "%\">" . mysqli_fetch_field_direct($result, $i)->name ."</th>";
                    			$i++;
                    		}
                    		echo "</tr>";
                    			
                    		//display the data
                    		while ($cols = mysqli_fetch_array($result, MYSQLI_ASSOC))
                    		{
                    			echo "<tr onmouseover='ChangeColor(this, true);' onmouseout='ChangeColor(this, false);' onclick=\"document.location.href = '/service/individualHistory.php?id=".$cols['sc_id']."'\">";
                    			foreach ($cols as $data)
                    			{
                    				echo "<td align='left'>" . $data . "</td>";
                    			}
                    			echo "</tr>";
                    		}
                    	}else{
                    		echo "<tr><td colspan='" . ($i+1) . "'>No Results found!</td></tr>";
                    	}
                    	echo "</table>";
                    }
                    else{
                    	echo "<tr><td colspan='" . ($i+1) . "'>No Results found!</td></tr>";
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