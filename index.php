<?php 
	require_once("includes/config.php");
	//Get the current slug from URI
	//www.jon.com/gallery?t=1
	$currentURI = $_SERVER['REQUEST_URI'];
	$currentURI = substr($currentURI, 0, strpos($currentURI, "?"));
	$currentURIArray = explode('/',$currentURI);
	$currentSlug = $currentURIArray[1];


	if(!empty($_GET['type'])){
		$type = $_GET['type'];
		if($type ==='journal'){
			if(!empty($_GET['subtype'])){
				$subtype = $_GET['subtype'];
				if($subtype ==='landing'){
					$currentSeason = getSeasonAndYearByDate(date('Y-m-d'));
					$sql = "SELECT * FROM `news` order by `date` DESC";
					$result  = mysqli_query($conn,$sql);
					$responseArray = array();
					// $mainContent = '<h5 id="page-title">Writing</h5>';
					$mainContent .= '<div id="page-content">';
					while($journalPost = mysqli_fetch_object($result)){
						$postSeason = getSeasonAndYearByDate($journalPost->date);
						if($currentSeason === $postSeason){
							$mainContent .= '<a class="news-link" href="journal/'.$currentSeason.'/'.$journalPost->title.'" class="journal-post-readmore" data-slug="'.$journalPost->title.'"  data-season="'.$currentSeason.'" ><div class="news-box">';
							$mainContent .= '<div class="news-image" style="background-image: url(\''.BASEURL.'images/news/thumbnails/'.$journalPost->image.'\')"></div>';
							$mainContent .= '<div class="news-context">';
							$mainContent .= '<h2 class="news-item-title">'.$journalPost->title.'</h2>';
							$mainContent .= '<span class="news-date news-item-date">'.date('F jS Y',strtotime($journalPost->date)).'</span>';
							$mainContent .= '<p class="news-item-content">'.$journalPost->excerpt.'</p>';
							$mainContent .= '</div></div></a>';
						}
					}
					$mainContent .='</div>';
				}else if($subtype ==='category'){
					if(!empty($_GET['catSlug'])){
						$catSlug = $_GET['catSlug'];
						$sql = "SELECT * FROM `news` order by `date` DESC";
						$result  = mysqli_query($conn,$sql);
						$responseArray = array();
						$mainContent = '<div id="page-content">';
						while($journalPost = mysqli_fetch_object($result)){
							$postSeason = getSeasonAndYearByDate($journalPost->date);
							if($catSlug === $postSeason){
								$mainContent .= '<a class="news-link" href="'.$journalPost->title.'" class="journal-post-readmore" data-slug="'.$journalPost->title.'"  data-season="'.$catSlug.'" ><div class="news-box">';
								$mainContent .= '<div class="news-image" style="background-image: url(\''.BASEURL.'images/news/thumbnails/'.$journalPost->image.'\')"></div>';
								$mainContent .= '<div class="news-context">';
								$mainContent .= '<h2 class="news-item-title">'.$journalPost->title.'</h2>';
								$mainContent .= '<span class="news-date news-item-date">'.date('F jS Y',strtotime($journalPost->date)).'</span>';
								$mainContent .= '<p class="news-item-content">'.$journalPost->excerpt.'</p>';
								$mainContent .= '</div></div></a>';
							}
						}
						$mainContent .= '</div>';
						//Following line only works when an ajax request from journal category is being send
						if(!empty($_GET['ajax']) && $_GET['ajax']=='true'){
							echo $mainContent;
							exit();
						}
					}
				}else if($subtype ==='post'){
					if(!empty($_GET['postSlug'])){
						$catSlug = $_GET['catSlug'];
						$postSlug = $_GET['postSlug'];
						$sql = "SELECT * FROM `news` WHERE `title`='$postSlug'";
						$result  = mysqli_query($conn,$sql);
						$journalPost = mysqli_fetch_object($result);

						$mainContent .= '<div id="page-content">';
						$mainContent .= '<div class="news-box">';
						$mainContent .= '<div class="post-back-button" data-id="'.$catSlug.'"><a href="http://jonthanks.com/journal/'.$catSlug.'">Back to '.$catSlug.'</a></div>';
						$mainContent .= '<h1 class="news-item-title">'.$journalPost->title.'</h1>';
						$mainContent .= '<span class="news-date news-item-date">'.date('F jS Y',strtotime($journalPost->date)).'</span>';
						$mainContent .= '<p class="news-item-content">'.$journalPost->body.'</p>';
						$mainContent .= '</div>';
						$mainContent .= '</div>';

						if(!empty($_GET['ajax']) && $_GET['ajax']=='true'){
							echo $mainContent;
							exit();
						}
					}
				}
				//Generate Journal sidebar
				if(!empty($currentSeason)){
					$currentSeasonName = $currentSeason;
				}else if(!empty($catSlug)){
					$currentSeasonName = $catSlug;
				}else{
					$currentSeasonName = "";
				}
				$sql = "SELECT * FROM `news` order by `date` DESC";
				$tempArray = array();
				$result = mysqli_query($conn,$sql);
				while($row = mysqli_fetch_object($result)){
					$tempArray[] =  getSeasonAndYearByDate($row->date);
				}
				$categories= array_unique($tempArray);
				foreach ($categories as $category) {
					$current = ($currentSeasonName === $category)? 'current' : '';
					$sidebar .= '<div  class="nav-item journal-category" data-id="'.$category.'"><a href="'.BASEURL.'journal/'.$category.'" class="'.$current.'">'.$category.'</a></div>';
				}
			}
		}else if($type ==='gallery'){
			$mainContent = '';
			if(empty($_GET['ajax'])){
				$mainContent .='<h5 id="page-title">Photogallery</h5>';
			}
			$mainContent .='<div id="page-content"><div class="gallery"> '; 
				if(!empty($_GET['category'])){
					$categoryName = $_GET['category'];
					$sql = "SELECT * FROM `gallery` JOIN `gallerysections` ON `gallery`.`categoryId` = `gallerysections`.`id` WHERE `gallerysections`.`title`='".$categoryName."' ";
				}else{
					$sql = "SELECT * FROM `gallery` WHERE `favorite`=1";
				}      
				$result  = mysqli_query($conn,$sql);
				$responseArray = array();
				while($image = mysqli_fetch_object($result)){
					$mainContent .= '<div class="gallery-item lightbox-trigger" data-original="'.BASEURL.'images/gallery/'.$image->original.'" title="'.$image->name.'" data-categoryId="'.$image->categoryId.'" style="background-image:url(\''.BASEURL.'images/gallery/thumbnails/'.$image->thumb.'\');">&nbsp;</div>';
				}
			$mainContent .='</div></div>';

			if(!empty($_GET['ajax']) && $_GET['ajax']=='true'){
				echo $mainContent;
				exit();
			}

			//Get the categories
			$sql = "SELECT * FROM `gallerysections`";
			$result  = mysqli_query($conn,$sql);
			while($galleryCategory = mysqli_fetch_object($result)){
				//highlight the current cat
				if(!empty($_GET['category'])){
					if($_GET['category']==$galleryCategory->title){
						$current = 'current';
					}else{
						$current = '';
					}
				}
				$sidebar .= '<div class="nav-item gallery-category"><a class="'.$current.'" href="gallery/'.$galleryCategory->title.'/" data-cattitle="'.$galleryCategory->title.'">'.$galleryCategory->title.'</a></div>';
			}
		}else if($type ==='page'){
			$slug = $_GET['slug'];
			$sql = "SELECT * FROM `pages` WHERE `slug`='$slug'";
			$result  = mysqli_query($conn,$sql);
			$row = mysqli_fetch_object($result);
			$mainContent = "<div id='page-content'>".$row->content."</div>";
			$sidebar = $row->sidebar;
		}
	}else{
		$slug = 'home';
		$sql = "SELECT * FROM `pages` WHERE `slug`='$slug'";
		$result  = mysqli_query($conn,$sql);
		$row = mysqli_fetch_object($result);
		$mainContent = "<div id='page-content'>".$row->content."</div>";
		$sidebar = $row->sidebar;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Jon Xie</title>
	<meta name="google-site-verification" content="Dc6F0iQ_vmr706EOW_Wy2vWJ9oDESWUMlM5CVoQ1j5I" />
	<link rel="shortcut icon" href="images/fav.ico" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="<?=BASEURL ?>css/styles.css?v=<?= filemtime(BASEPATH.'css/styles.css') ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-124277890-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-124277890-1');
	</script>


</head>

<body class="frontend <?=$slug ?>">
	<header>
		<nav class="main-nav">
			<?php 
				$sql = "SELECT * FROM `pages` ORDER BY `priority` DESC";
				$result  = mysqli_query($conn,$sql);
				while($page = mysqli_fetch_object($result)){
			?>
				<div class="nav-item nav-item-center"><a href="<?=(($page->slug=='home')? '/' : BASEURL.$page->slug) ?>" class="ajax-link <?=($currentSlug==$page->slug || ($currentSlug=='' && $page->slug=='home') || ($slug=='home' && $page->slug=='home'))?'current':'' ?>" data-type="page" data-destination="page-content.php?p=<?php echo($page->slug);?>" data-slug="<?php echo($page->slug);?>" ><?php echo($page->title);?></a></div>
			<?php 
				}
			?>
			<div class="nav-item nav-item-center"><a href="<?=BASEURL?>gallery" class="ajax-link  <?=($currentSlug=='gallery')?'current':'' ?>" data-type="gallery" data-destination="gallery-content.php" data-slug="gallery">Photo Gallery</a></div>
			<div class="nav-item nav-item-center"><a href="<?=BASEURL?>journal" class="ajax-link  <?=($currentSlug=='journal')?'current':'' ?>" data-type="journal" data-destination="journal-content.php"  data-slug="journal">Journal</a></div>
		</nav>
		<div class="clearfix"></div>
		<div id="page-sidebar">
			<?php 
				echo($sidebar);
			?>
		</div>
		<div class="ajax-loading"><img style="height: 50px; width: 50px;" src="images/boxy.gif"></div> 
	</header>
	<!-- <div class="ajax-loading-home"><img style=" height: 100px; width: 120px;" src="images/eq.svg"></div>-->
	<div class="header-divider"></div>
	<main class="main-container">
		<?php 
			echo($mainContent);
		?>
	</main>
	<div class="clearfix"></div>
	<div class="lightbox">
		<div class="lightbox-inner">
			<span class="arrow-left">
				<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 477.175 477.175" style="enable-background:new 0 0 477.175 477.175;" xml:space="preserve">
					<g>
						<path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225
							c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"/>
					</g>
				</svg>
			</span>
			<span class="arrow-right">
				<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 477.175 477.175" style="enable-background:new 0 0 477.175 477.175;" xml:space="preserve">
					<g>
						<path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225
							c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z"/>
					</g>
				</svg>
			</span>
			<div class="lightbox-close">
				<svg width="64" version="1.1" xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 0 64 64" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 64 64">
				  <g>
				    <path d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
				  </g>
				</svg>
			</div>
			<div class="lightbox-content">
			</div>
		</div>
	</div>
	<script>
		var BASEURL = "<?=BASEURL ?>";
		var BASEPATH = "<?=BASEPATH ?>";
	</script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="<?=BASEURL ?>js/main.js?v=<?= filemtime(BASEPATH.'js/main.js') ?>"></script>
</body>
</html>

<!--# F F 1 9 3 5 D 0 red-->
