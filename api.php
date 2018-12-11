<?php 
require_once("includes/config.php");
require_once('classes/Api.php');
//API END POINT
$myApi = new Api('nimda');
$result = $myApi->init();
echo $result;
