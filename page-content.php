<?php 
	require_once("includes/config.php");
	$slug = $_GET['p'];
	$sql = "SELECT * FROM `pages` WHERE `slug`='$slug'";
	$result  = mysqli_query($conn,$sql);
	$row = mysqli_fetch_object($result);

	echo(json_encode($row));
?>

