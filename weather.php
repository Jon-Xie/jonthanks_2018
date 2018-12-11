<?php 
	$location = 'Dallas TX';
	if(!empty($_POST['location'])){
		$location = $_POST['location'];
	}
	$destURL = 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text%3D%22'.$location.'%2C%20CA%22)&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';
	$ch = curl_init($destURL);
	curl_setopt($ch, CURLOPT_HEADER,false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	$resultJSON = curl_exec($ch);
	$resultArray =  json_decode($resultJSON,true);
	echo '<pre>';
	print_r($resultArray);
?>

<?php 
	if(!empty($resultArray)){
?>
	<h1><?php print_r($resultArray['query']['results']['channel']['location']['city']); ?></h1>
<?php 
	}
?>


<form method="POST">
	<input type="text" name="location" placeholder="Dallas"><br>
	<input type="submit" name="" value="Search">
</form>