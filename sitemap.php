<?php
	require_once("includes/config.php");

	function sitemapHeader(){
		echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
	}

	function sitemapAddUrl($url){
		echo '<url><loc>'.$url.'</loc></url>';
	}

	function sitemapFooter(){
		echo '</urlset>';
	}

	header("Content-type: text/xml");
	sitemapHeader();
	sitemapAddUrl('http://jonthanks.com/#gallery');
	$sql = "SELECT * FROM `pages` ORDER BY `priority` DESC";
	$result  = mysqli_query($conn,$sql);
	while($page = mysqli_fetch_object($result)){
		$tail =(($page->slug=='home')? '' : '#'.$page->slug);
		sitemapAddUrl('http://jonthanks.com/'.$tail);
	}
	sitemapFooter();