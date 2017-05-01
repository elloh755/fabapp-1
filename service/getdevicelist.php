<?php
/*
 * CC BY-NC-AS UTA FabLab 2016-2017
 * FabApp V 0.9
 */
include_once ($_SERVER ['DOCUMENT_ROOT'] . '/pages/header.php');

?>

<?php 

$dg_id = $_GET["dg_id"];


if($dg_id !=""){
	$result = $mysqli->query ( "SELECT * FROM devices where dg_id =$dg_id AND public_view = 'Y'" );
	
	echo "<select>";
	while($row = mysqli_fetch_array($result))
	{
		echo '<option value="'.$row["d_id"].'">'; echo $row["device_desc"]; echo "</option>";
	}
	echo "</select>";
	
}



?>





<?php
// Standard call for dependencies
include_once ($_SERVER ['DOCUMENT_ROOT'] . '/pages/footer.php');
?>