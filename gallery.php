<?php 
	require_once("includes/config.php");
	$perPage = 3;
	$page = (!empty($_GET['page']))? $_GET['page'] : 1 ;
	$startPosition = ($page - 1) * $perPage; 

	$res0 = mysqli_query($conn, "SELECT * FROM gallery");
	$totalCount = mysqli_num_rows($res0);

	$res = mysqli_query($conn, "SELECT * FROM gallery LIMIT $startPosition,$perPage");
	while($row = mysqli_fetch_object($res)){
		echo $row->name;
		echo '<hr>';
	}
?>