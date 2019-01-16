<?php 
function thumbGen($src, $dest, $desired_width) {
	if(exif_imagetype($src) === IMAGETYPE_JPEG){
		/* read the source image */
		$source_image = imagecreatefromjpeg($src);
	}else if(exif_imagetype($src) === IMAGETYPE_PNG){
		$source_image = imagecreatefrompng($src);
	}else{
		copy($src,$dest);
	}
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresized($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest, 100);
}

function getSeasonAndYearByDate($date){
	$unixDate = strtotime($date);
	$year = date('Y',$unixDate);
	$month = date('F',$unixDate);
	$day = date('d',$unixDate);
	$today = new DateTime($month.' '.$day);
	// echo $date; exit();
	$spring = new DateTime('March 20');
	$summer = new DateTime('June 20');
	$fall = new DateTime('September 22');
	$winter = new DateTime('December 21');

	if($today >= $spring && $today < $summer){
		$season = 'Spring';
	}else if($today >= $summer && $today < $fall){
		$season = 'Summer';
	}else if($today >= $fall && $today < $winter){
		$season = 'Fall';
	}else{
		$season = 'Winter';
	}
	if($month=="December" && $day>=21 && $day<=31){
		$year++;
		$season = 'Winter';
	}

	$output = $year.' '.$season;
	return $output;
}