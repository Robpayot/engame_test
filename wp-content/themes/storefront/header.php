<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php storefront_html_tag_schema(); ?>>
<head>
	<script>
		//Have we ever setup on this website
		var egObj = localStorage.egObj;
		var enGameLocal =  null;
		var enGameLoaded = null;
		var shouldPlay = localStorage.egShouldPlay;

		if(egObj){
			//Check elapsed time
			var elapsedTime = Date.now() - localStorage.egTime;
			console.log('Elapsed time no mans land (BackEnd) : ' + String(elapsedTime) + ' milliseconds');

			//Instantiate the game from localStorage
			enGameLocal = new Function("return ("+JSON.parse(egObj)+")")()();
			enGameLocal.create(true);

			if(elapsedTime > 30000){
				//It's been there for a very long time
				localStorage.clear();
			}else if(elapsedTime > 1000){
				localStorage.egShouldPlay = true;
				//If time > 3 seconds, load the content;
				if(shouldPlay == "true"){
					//Play or continue animation
					//Get the last timecode
					//var timecode = localStorage.egAnimTimecode;
					console.log("Play an anim");
					//enGameLocal.playAnim();
				}
			}else{
				localStorage.egShouldPlay = false;
			}
		}

		//Same as DOMContentLoaded but IE ok
		document.onreadystatechange = function () {

			if (document.readyState == "interactive") {

				if(egObj){
					elapsedTime = Date.now() - localStorage.egTime;
					console.log('Elapsed time on dom content loaded (FrontEnd) : ' + String(elapsedTime) + ' milliseconds');

					if(enGameLocal && enGameLocal != null && enGameLocal != undefined){
						enGameLocal.dispose(); //Hide the loader
					}
				}

				//Load the selected game in async
				var request;
				if (window.XMLHttpRequest) {
					request = new XMLHttpRequest();
					} else {
					// code for IE6, IE5
					request = new ActiveXObject("Microsoft.XMLHTTP");
				}
				request.open("GET", window.location.origin + "/js/egScript.js?v=" + Math.random(), true);
				request.send();

				request.onreadystatechange = function(){
					if( (request.readyState === 4) && (request.status === 200) ) {
						enGameLoaded = eval(request.responseText); //For security reason, change eval() to JSON.parse()
						//Put it in the localStorage
						localStorage.egObj = JSON.stringify(enGameLoaded.toString());
						enGameLoaded = enGameLoaded();

						if(enGameLoaded && enGameLoaded != null && enGameLoaded != undefined){
							enGameLoaded.create(false); //Create the loader
						}
					}
				}

				//Destroy the old loader
				setTimeout(function(){
					if(enGameLocal && enGameLocal != null && enGameLocal != undefined && egObj){
						enGameLocal.destroy();
						localStorage.removeItem('egAnimTimecode');
					}
				}, 1000);


			}
		}

		//When user quit the page
		window.addEventListener('beforeunload', function() {
			//Store the date
			localStorage.egTime = Date.now();
			if(enGameLoaded && enGameLoaded != null && enGameLoaded != undefined){
				enGameLoaded.draw(); //Anim In the loader
				if(localStorage.egShouldPlay == "true"){
					//Play a part of the animation ?
					//enGameLoaded.play();
				}
			}
		}, false);
		//Put timecode at the last moment possible
		window.addEventListener('unload', function(){
			//Store anim timecode to continue it
			localStorage.egAnimTimecode = Date.now();
		}, false);
	</script>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>


<div id="page" class="hfeed site">
	<?php
	do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" <?php if ( get_header_image() != '' ) { echo 'style="background-image: url(' . esc_url( get_header_image() ) . ');"'; } ?>>
		<div class="col-full">

			<?php
			/**
			 * @hooked storefront_skip_links - 0
			 * @hooked storefront_social_icons - 10
			 * @hooked storefront_site_branding - 20
			 * @hooked storefront_secondary_navigation - 30
			 * @hooked storefront_product_search - 40
			 * @hooked storefront_primary_navigation - 50
			 * @hooked storefront_header_cart - 60
			 */
			do_action( 'storefront_header' ); ?>

		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		/**
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'storefront_content_top' ); ?>
