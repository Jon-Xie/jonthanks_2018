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
	$month = date('n',$unixDate);
	$year = date('Y',$unixDate);
	if($month>= 3 && $month <= 4){
		$season = 'Spring';
	}else if($month >= 5 && $month <=8){
		$season = 'Summer';
	}else if($month >= 9 && $month <=11){
		$season = 'Autumn';
	}else if($month >= 12 || $month <=2){
		$season = 'Winter';
	}
	$output = $year.' '.$season;
	return $output;
}