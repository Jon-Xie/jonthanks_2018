<?php 
	require_once("includes/config.php");
	//Get the images
	$sql = "SELECT * FROM `gallery` WHERE `favorite`=1";
	$result  = mysqli_query($conn,$sql);
	$responseArray = array();
	while($row = mysqli_fetch_object($result)){
		$responseArray['images'][] = array(
			'name' => $row->name,
			'thumb' => 'images/gallery/thumbnails/'.$row->thumb,
			'original' => 'images/gallery/'.$row->original,
			'categoryId' => $row->categoryId
		);
	}
	//Get the categories
	$sql = "SELECT * FROM `gallerysections`";
	$result  = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_object($result)){
		$responseArray['categories'][] = array(
			'id' => $row->id,
			'title' => $row->title,
		);
	}

	echo(json_encode($responseArray));
?>
