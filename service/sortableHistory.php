<?php 
   include_once ($_SERVER['DOCUMENT_ROOT'].'/connections/db_connect8.php');
   include_once ($_SERVER['DOCUMENT_ROOT'].'/connections/ldap.php');
   include_once ($_SERVER['DOCUMENT_ROOT'].'/class/all_classes.php');
   
   
   if ($mysqli->connect_errno) {
   	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
   }
?>
<!-- For making this page work, footers and headers need some adjustments -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
   <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <title>Service Call History</title>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>
      <script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
      <script type="text/javascript" charset="utf-8">
         $(document).ready(function() {
         	$('#example').DataTable();
         } );
      </script>
   </head>
   <body>
      <div class="container">
         <table id="example" class="display" cellspacing="0" width="100%">
            <thead>
               <tr>
                  <th> Device Name</th>
                  <th> Opened</th>
                  <th> By </th>
                  <th> Solved</th>
                  <th> Notes</th>
               </tr>
            </thead>
            <tbody>
               <?php 
                  $result = $mysqli->query ( "SELECT * FROM service_call" );
                  $deviceList = $mysqli->query("SELECT d_id, device_desc FROM devices");
                  $deviceName = "";
                  
                  while($row = mysqli_fetch_array($result))
                  {
                  	echo "<tr>";
                  	
                  	echo "<td>";
                  	foreach($deviceList as $rowDev){
                  		if($row['d_id'] == $rowDev['d_id']){
                  			$deviceName = $rowDev['device_desc'];
                  		}
                  	}
                  	
                  	echo $deviceName;
                  	echo "</td>";
                  
                  	echo "<td>";
                  	echo $row['sc_time'];
                  	echo "</td>";
                  	
                  	echo "<td>";
                  	echo $row['staff_id'];
                  	echo "</td>";
                  	
                  	echo "<td>";
                  	if($row['solved'] == 'Y'){
                  		echo 'Finished';
                  	} else {
                  		echo 'Incomplete';
                  	}
                  	echo "</td>";
                  
                  	echo '<td>';
                  	echo $row['sc_notes'];
                  	echo '</td>';
                  	echo "</tr>";
                  }
                  ?>
               
            </tbody>
         </table>
      </div>
      <script type="text/javascript">
         // For demo to fit into DataTables site builder...
         $('#example')
         	.removeClass( 'display' )
         	.addClass('table table-striped table-bordered');
      </script>
   </body>
</html>