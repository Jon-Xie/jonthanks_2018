<?php 
	require_once("includes/config.php");
	$sql = "SELECT * FROM `news` order by `date` DESC";
	$result  = mysqli_query($conn,$sql);
	$responseArray = array();
	$currentSeason = getSeasonAndYearByDate(date('Y-m-d'));
	while($row = mysqli_fetch_array($result)){
		$postSeason = getSeasonAndYearByDate($row['date']);
		if($currentSeason === $postSeason){
			$row['season'] = $currentSeason;
			$row['date'] = date('F jS Y',strtotime($row['date']));
			$responseArray['posts'][] = $row;
		}
	}

	$tempArray = array();
	$result  = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_object($result)){
		$tempArray[] =  getSeasonAndYearByDate($row->date);
	}
	$responseArray['currentSeason'] = $currentSeason;
	$responseArray['categories'] = array_unique($tempArray);
	echo(json_encode($responseArray));
?>  
