<?php 
	require_once("includes/config.php");
	$sql = "SELECT * FROM `news`";
	$result  = mysqli_query($conn,$sql);
	$responseArray = array();
	while($row = mysqli_fetch_object($result)){
		$responseArray[] = $row;
	}

	echo(json_encode($responseArray));
?>
