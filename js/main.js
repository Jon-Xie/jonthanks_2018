$(document).ready(function(){
	var fadeOutDelay = 450;
	isHome();
	adjustGalleryImageSize();
	// $('.slider').flickity({
	//   // options
	//   cellAlign: 'left',
	//   contain: false,
	//   autoPlay: true,
	// });

/*
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
	    acc[i].addEventListener("click", function() {
	        /* Toggle between adding and removing the "active" class,
	        to highlight the button that controls the panel 
	        this.classList.toggle("active");

	        /* Toggle between hiding and showing the active panel
	        var panel = this.nextElementSibling;
	        if (panel.style.display === "block") {
	            panel.style.display = "none";
	        } else {
	            panel.style.display = "block";
	        }
	    });
	} 
	*/
	$(window).resize(function(){
		adjustGalleryImageSize();
	});

	function adjustGalleryImageSize(){
		$('.gallery-item').each(function(){
			var width = $(this).width();
			$(this).height(width);
		});
	}
	
	$(document).on('click', '.accordion', function(){
		$(this).toggleClass('active');
		$(this).siblings('.panel').slideToggle();
	});
	//Load homepage content using Ajax
	



	//Navigation Deep linking //if user provides a hash, if happens
	if(window.location.hash!==undefined && window.location.hash !==''){
		var hashConvertedToSlug = window.location.hash.substring(1);
		
		$('.main-nav .nav-item a').each(function(){
			var slugFromMenu = $(this).data('slug');
			if(hashConvertedToSlug == slugFromMenu){
				var destination = $(this).data('destination');//contact.html
				var type = $(this).data('type');
				var slug = $(this).data('slug');
				navigate(this,slug,type,destination); 
			}
		});                         
	}else{
		// $('body').css('background-color', 'black');
		// $('body').css('color', '#fff');
		// $.ajax({ //xhr request ~20 lines code in vanilla
		// 	url : 'page-content.php?p=home', //home
		// 	method : "GET",
		// }).done(function(JSONresponse){
		// 	//If type is equal to page. Make another ajax call to template-page.php to get the template
		// 	$.ajax({ 
		// 		url : 'template-page.php',
		// 		method : "GET",
		// 	}).done(function(template){
		// 		console.log('hi');
		// 		$('.main-container').html(template);
		// 		var responseObject = JSON.parse(JSONresponse);
		// 		$('#page-title').html(responseObject.title);
		// 		$('#page-content').html(responseObject.content);
		// 		$('#page-sidebar').html(responseObject.sidebar);
		// 		$(".main-nav").hide().fadeIn(1000);
		// 		$(".main-container").hide().fadeIn(1000);
		// 		$("a:first").addClass('current');
		// 		$('.frontend a:visited, .frontend  a:link').css('color','white');
		// 		$('#page-sidebar').hide().fadeIn(1000);

		// 	});
		// 	//dsadasdasd if statement here for fade out??? sadasd
		// });
	}



	$('.ajax-link').click(function(e){
		e.preventDefault();
		var slug = $(this).data('slug');
		var destination = $(this).data('destination');//contact.html
		var type = $(this).data('type');
		if(slug==='home'){
			changeURL();
			navigate(this,slug,type,destination); 
		}else{
			changeURL(slug);
			navigate(this,slug,type,destination); 
		}
	});

	function changeURL(slug = ''){ //default 
		if(slug == ''){
			var destinationURL = getDomain(window.location.href);
		}else{
			var domain = getDomain(window.location.href);
			var destinationURL = domain + slug;
		}
		window.history.pushState({"html":'',"pageTitle":slug},"",destinationURL);
	}

	function getDomain(url){
		var urlArray = url.split('/');
		return urlArray[0]+"//"+urlArray[2]+'/';
	}

	function isHome(){
		var url = window.location.href;
		var urlArray = url.split('/');
		console.log(urlArray);
		if(urlArray.length===4 && urlArray[3]==''){
			$('body').addClass('home');
			return true;
		}else{
			if(urlArray[3]!=''){
				if(urlArray[3].substring(0,1) != '?'){
					$('body').removeClass('home');
					return false;
				}
			}else{
				$('body').removeClass('home');
				return false;
			}
		}
	}


	function navigate(element,slug,type,destination){	
		//Handle Menu Ajax requests
		isHome();
		$('.sub-nav').fadeOut(100);
		$('.main-container').fadeOut(fadeOutDelay);
		$('body').addClass('bodyInvert'); 


		//Change the current element color
		$('.main-nav a').each(function(){
			$(this).removeClass('current');
		});
		$(element).addClass('current');

		var navigateParams = {
			'element': element,
			'slug': slug,
			'type': type,
			'destination' : destination
		};

		setTimeout(function(navigateParams){
			console.log('here');
			console.log(navigateParams);
			var destination = navigateParams.destination;
			var type = navigateParams.type;
			var that = navigateParams.element;
			var slug = navigateParams.slug;
			if(slug == 'home'){ // www.com#a
				$('.frontend a:visited, .frontend  a:link').css('color','white');
			}else{
				$('.frontend a:visited, .frontend  a:link').css('color',' #353535FF');
			}
			$.ajax({ //xhr request ~20 lines code in vanilla
				url : destination,
				method : "GET",
			}).done(function(JSONresponse){
				if(type == 'page'){ 
					// $(".main-container").hide().fadeIn(1000);
					$.ajax({ 
						url : 'template-page.php',
						method : "GET",
						beforeSend : function(){
							if(slug === 'home'){
								$('.ajax-loading-home').fadeIn();
							}else{
								$('.ajax-loading-other').fadeIn();
							}
						},
					}).done(function(template){
						console.log(template);
						$('.main-container').html(template);
						var responseObject = JSON.parse(JSONresponse);
						// $('#page-title').html(responseObject.title);
						$('#page-content').html(responseObject.content);
						$('#page-sidebar').html(responseObject.sidebar);

						fadeSubNavItems();
						//$('#page-sidebar').hide().delay(500).fadeIn(1000); // I tried adding here
						if(slug === 'home'){
							$('.ajax-loading-home').fadeOut();
						}else{
							$('.ajax-loading-other').fadeOut();
						}
						$(".main-container").delay(300).hide().fadeIn(1000);
					});
				}else if(type == 'journal'){
					$(".main-container").hide().delay(300).fadeIn(1000);
					$.ajax({ 
						url : 'template-journal.php',
						method : "GET",
						beforeSend : function(){
							$('.ajax-loading').fadeIn();
							console.log('start');
						},
					}).done(function(template){
						$('#page-sidebar').html('');
						$('.main-container').html(template);
						var innertemplate = $('#page-content');
						var responseObject = JSON.parse(JSONresponse);
						var journalBox = '';
						if(responseObject !==undefined && responseObject.posts !==undefined){
							for(var i=0; i< responseObject.posts.length; i++){
								var journalItem = responseObject.posts[i];
								$(innertemplate).find('.news-item-title').html(journalItem.title);
								$(innertemplate).find('.news-item-content').html(journalItem.excerpt);
								$(innertemplate).find('.news-item-date').html(journalItem.date);
								$(innertemplate).find('.news-image').css('background-image','url("'+BASEURL+'images/news/thumbnails/'+journalItem.thumbnail+'")');
								$(innertemplate).find('.news-link').attr("href","journal/"+journalItem.season+"/"+journalItem.title);
								$(innertemplate).find('.news-link').attr("data-slug",journalItem.title);
								$(innertemplate).find('.news-link').attr("data-season",journalItem.season);
								journalBox += $(innertemplate).html(); //object innertemplate
								//$('#page-content').append($(innertemplate).html()); This is not going to work!!!
							}
						}
						$('#page-content').html(journalBox); //string with elements too
						//bug with 8 septs vs 4 that we wanted 
						//Show sidebar categories for journal
						var currentSeason = responseObject.currentSeason;
						var sidebarContent = '';
						for(key in responseObject.categories){
							var categoryItem = responseObject.categories[key];
							if(currentSeason==categoryItem){
								var currentClass = 'current';
							}else{
								var currentClass = '';
							}
							sidebarContent += '<div class="nav-item journal-category" data-id="'+categoryItem+'"><a href="'+BASEURL+'journal/'+categoryItem+'" class="'+currentClass+'">'+categoryItem+'</a></div>';
						}
						$('#page-sidebar').html(sidebarContent);

						fadeSubNavItems();
						//$('#page-sidebar').hide().fadeIn(1000);

						$('.ajax-loading').fadeOut();
					});
				}else if(type == 'gallery'){
					$('.ajax-loading-other').fadeIn();
					$.ajax({ 
						url : 'template-gallery.php',
						method : "GET",
					}).done(function(template){
						console.log(JSONresponse);
						$('#page-sidebar').html('');
						$('.main-container').html(template);
						var innertemplate = $('.gallery');
						var responseObject = JSON.parse(JSONresponse);
						var imageBoxes = '';
						if(responseObject.images!=undefined){
							for(var i=0; i< responseObject.images.length; i++){
								var imageItem = responseObject.images[i];
								$(innertemplate).find('.gallery-item').css('background-image',"url('"+imageItem.thumb+"')");
								$(innertemplate).find('.gallery-item').attr('data-original',imageItem.original); //.data doesnt work
								$(innertemplate).find('.gallery-item').attr('title',imageItem.name);
								$(innertemplate).find('.gallery-item').attr('data-categoryId',imageItem.categoryId);
								imageBoxes += $(innertemplate).html(); 
							}
						}
						imageBoxes += '<img class="preloader">';
						$('.gallery').html(imageBoxes); 
						$(".main-container").delay(800).hide().fadeIn(1000);
						//Show sidebar categories for gallery
						var sidebarContent = '';
						for(var i=0; i< responseObject.categories.length; i++){
							var categoryItem = responseObject.categories[i];
							sidebarContent += '<div class="nav-item gallery-category"><a href="/gallery/'+categoryItem.title+'"  data-cattitle="'+categoryItem.title+'">'+categoryItem.title+'</a></div>';
						}
						$('#page-sidebar').html(sidebarContent);

						fadeSubNavItems();
						// $('#page-sidebar').hide().delay(500).fadeIn(1000);

						//Preload images
						if(responseObject.images!=undefined){
							for(var i=0; i< responseObject.images.length; i++){
								var imageItem = responseObject.images[i];
								var onFlyImage = new Image();
								onFlyImage.src = imageItem.original; //pure js, no cashed 
								console.log(onFlyImage);
							}
						}

						$('.ajax-loading-other').fadeOut();
					});
				}else{
					$('.main-container').html(JSONresponse);
				}
			});
		},fadeOutDelay,navigateParams); 
	}

	$(document).on('click','.journal-category a', function(e){
		e.preventDefault();
		var that = this;
		$('.main-container').fadeOut(fadeOutDelay,function(){
			var href = $(that).attr('href');
			var slug = $(that).parent('div').data('id');	
			$.ajax({ 
				url : href+"/ajax/",
				method : "GET",
			}).done(function(response){
				changeURL('journal/'+slug+'/');
				$('#page-content').html(response); 
				$(".main-container").delay(300).hide().fadeIn(1000);
			});
		});
		$('.journal-category a').removeClass('current');
		$(this).addClass('current');
	});

	$(document).on('click','.post-back-button a', function(e){
		e.preventDefault();
		var id = $(this).parent('div').data('id');
		var that = this;
		$('.main-container').fadeOut(fadeOutDelay,function(){
			var href = $(that).attr('href');
			var slug = $(that).parent('div').data('id');	
			$.ajax({ 
				url : href+"/ajax/",
				method : "GET",
			}).done(function(response){
				changeURL('journal/'+slug+'/');
				$('#page-content').html(response); 
				$(".main-container").delay(300).hide().fadeIn(1000);
			});
		});
		$('.journal-category a').removeClass('current');
		$('.journal-category a').each(function(){
			if($(this).parent('div').data('id') === id){
				$(this).addClass('current');
			}
		});
	});

	$(document).on('click','.news-link', function(e){
		e.preventDefault();
		console.log('readmore clicked');
		var that = this;
		$('.main-container').fadeOut(fadeOutDelay,function(){
			var href = $(that).attr('href');
			var slug = $(that).data('slug');
			var season = $(that).data('season');
			console.log(href+"/ajax/");
			$.ajax({ 
				url : href+"/ajax/",
				method : "GET",
			}).done(function(response){
				changeURL('journal/'+season+'/'+slug+'/');
				$('#page-content').html(response); 
				$(".main-container").delay(300).hide().fadeIn(1000);
			});
		});

	
	});

	//Filter gallery based on selected category
	$(document).on('click','.gallery-category a', function(e){
		e.preventDefault();
		$('.gallery-category a').each(function(){
			$(this).removeClass('current');
		});
		$(this).addClass('current');
		var that = this;
		$('.main-container').fadeOut(fadeOutDelay,function(){
			var category = $(that).data('cattitle');
			$.ajax({ 
				url : BASEURL+"gallery/"+category+"/ajax/",
				method : "GET",
			}).done(function(response){
				changeURL('gallery/'+category+'/');
				$('#page-content').html(response); 
				$(".main-container").delay(300).hide().fadeIn(1000);
				//Preload images
				$('#page-content .gallery-item').each(function(){
					var onFlyImage = new Image();
					onFlyImage.src = $(that).data('original');
					console.log(onFlyImage);
				})
				
			});
		});
	});



	$('.main-nav a:first').click(function(){
		$('body').removeClass('bodyInvert');
		// console.log('btyebye');
		// $('body').css('color', "#ffffff");
		// $('body').css('background-color', "black");
		// $('body').animate({
		// 		'color': "#ffffff",
		// 		'background-color': "black",
		// 		'z-index': 100
		// },3000);	
	}); 

	$('.main-nav a:not(:first)').click(function(){
		$('body').addClass('bodyInvert');
		// $('body').css('background-color', "white");
		// $('body').css('color', "#353535FF");
	});
	

	// //Ajax Spinner
	$('.ajax-loading').hide(); //bug ask Amir later  
	$(document).ajaxStart(function(){ //ajaxStart - ajaxStop = time of loading shown
		//if click listen is not the first nav-item a, then show spinner		
		$('.ajax-loading').show();
	}).ajaxStop(function(){
		$('.ajax-loading').fadeOut(400);
	}) //GENERIC FOR ALL AJAX, UP IS SPECIFIC 9/4/18
	


 
	//Page load spinner initial 
	$('.loading').hide();

	//Expandable content
	$(document).on('click',".expandable-description-trigger",function(){
		console.log("5log");
		$('.expandable-description').slideToggle();
	});

	//Lighbox 
	console.log('before light here');
	$(document).on("click",".lightbox-trigger", function(){
		console.log('in light');
		if($(this).hasClass('gallery-item')){
			$(this).addClass('current');
			var originalURL = $(this).data('original');
			var theImage = "<img src='"+originalURL+"'>";
			$('.lightbox-content').html(theImage);
			
		}
		console.log('light');
		$('.lightbox').fadeIn();
		$('.lightbox-close').click(function(){
			$('.gallery-item').removeClass('current');
			$('.lightbox').fadeOut();
		});
	});

	$(document).on("click",".arrow-left", function(){
		var prevItem = $('.gallery .current').prev();
		if(prevItem.length == 0){
			prevItem = $('.gallery .gallery-item').last();
		}
		$('.gallery-item').removeClass('current');
		$(prevItem).addClass('current');
		var originalURL = $(prevItem).data('original');
		var theImage = "<img src='"+originalURL+"'>";
		$('.lightbox-content').hide().html(theImage).fadeIn();
	});
	$(document).on("click",".arrow-right", function(){
		var nextItem = $('.gallery .current').next();
		if(nextItem.length == 0){
			nextItem = $('.gallery .gallery-item').first();
		}
		$('.gallery-item').removeClass('current');
		$(nextItem).addClass('current');
		var originalURL = $(nextItem).data('original');
		var theImage = "<img src='"+originalURL+"'>";
		$('.lightbox-content').hide().html(theImage).fadeIn();

	});
	


	$(document).on("click",".sub-nav a", function(e){
		if(this.hash !== ""){
			e.preventDefault(); //quick jump is removed and create our own 
			var dest = this.hash;
			console.log("hi1");
			$('.sub-nav a').off("scroll");
			$("html").animate({ //new jump
				scrollTop : $(dest).offset().top + 10
			},500);
		}	
		$('.sub-nav a').removeClass('current');
		$(this).addClass('current');

	});

	$(window).scroll(function(){
		$('.section').each(function(){
			var windowScrollTop = $(window).scrollTop();
			var currentElementTop = $(this).offset().top - 80;
			var currentElementBottom = currentElementTop + $(this).height();
			if(windowScrollTop >= (currentElementTop) && windowScrollTop <= currentElementBottom){
				var id = $(this).data('id');
				$('.sub-nav a').removeClass('current');
				$('#link-'+id).addClass('current');
			}
		});
	//Menu effect
	});

	//fade in transiiton as scrolling
	$('.hideme').each(function(){
		console.log("hisadad");
		// $(this).hide();
		// $(this).css('opacity',0);
		// $(thi.css('margin-top',70);
		console.log($(this).offset().top);
	});




	function fadeInMoveUp(windowScrollBottomPosition , threshold){
		$('.hideme').each(function(){
			var currentElementTop = $(this).offset().top;
			console.log(windowScrollBottomPosition);
			console.log(currentElementTop);
			if(windowScrollBottomPosition + threshold  >= currentElementTop){
				console.log('in');
				// $(this).animate({
				// 	'opacity': 1,
				// 	'margin-top' : 0
				// },3000)
				$(this).addClass("movingParagraph");
				$(this).css('opacity', 1);
			}
		});
	}
	$(window).scroll(function(){
		var windowHeight = $(this).height();
		var windowScrollTop = $(this).scrollTop();
		var windowScrollBottomPosition = windowScrollTop + windowHeight;
		// console.log(windowScrollBottomPosition);
		fadeInMoveUp(windowScrollBottomPosition , 5);
	});

	fadeSubNavItems();
});

function fadeSubNavItems(){
	var className = '#page-sidebar .nav-item';
	var delaySetting = 300;
	var delay = delaySetting;
	$(className).each(function(){
		var that = this;
		setTimeout(function(){fadeMeIn(that);},delay); //timer 
		delay += delaySetting;
	});
}

function fadeMeIn(element){
	$(element).addClass('go');
}



// function fadeTiles(tileClassName,delaySetting){
// 	var className = '.'+tileClassName;
// 	$(className).hide(); //fist hide the element
// 	var delay = delaySetting;
// 	$(className).each(function(){
// 		var that = this;
// 		setTimeout(function(){fadeMeIn(that);},delay); //timer 
// 		delay += delaySetting;
// 	});
// }

// function fadeMeIn(element){
// 	$(element).fadeIn();
// }