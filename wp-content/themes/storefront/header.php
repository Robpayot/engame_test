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
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
    <canvas id="myCanvas" width="578" height="200"></canvas>
    <script>

    </script>
	<script>
		//Have we ever setup on this website
		var enGameLocal = null;

		if(localStorage.egObj){
			//Check elapsed time
			var elapsedTime = Date.now() - localStorage.egTime;
			console.log('Elapsed time no mans land (BackEnd) : ' + String(elapsedTime) + ' milliseconds');

			if(elapsedTime > 30000){

				//It's been there for a very long time
				localStorage.clear();

			}else if(elapsedTime > 1000){
				//If time > 3 seconds, load the content
				//Instantiate the game from localStorage
				var enGame = localStorage.egObj;
				if(enGame){
					console.log("Object loaded from localStorage");

					enGameLocal = new Function("return ("+JSON.parse(enGame)+")")()();

					enGameLocal.draw();
				}
			}
		}

		var enGameLoaded = null;
		//Same as DOMContentLoaded but IE ok
		document.onreadystatechange = function (e) {

			console.log(document.readyState);

		    if (document.readyState == "interactive") {

				if(localStorage.egObj){
					//Delete the EnGame
					localStorage.removeItem("egObj");

					elapsedTime = Date.now() - localStorage.getItem("egTime");
					console.log('Elapsed time on dom content loaded (FrontEnd) : ' + String(elapsedTime) + ' milliseconds');

					enGameLocal.dispose();
				}

				//Load the selected game in async

				enGameLoaded = (function() {


					var _container = document.createElement('div');
					var _content = document.createElement('p').appendChild( document.createTextNode('Loading...') );
					console.log('append')
				    _container.appendChild(_content);
					_container.setAttribute('style', 'color:black;transition: all ease 2s;position:fixed;top:0;left:0;z-index:9999999;width:100%;height:100%;background:#fff;');
					setTimeout(function(){ 
						console.log('start anim')
						_container.setAttribute('style', 'color:red;transition: all ease 2s;position:fixed;top:0;left:0;z-index:9999999;width:100%;height:100%;background:#fff;'); 
					}, 10);

					
					return {

						create: function(){

						},

						draw: function(){
							// document.body.appendChild(_container);
						      window.requestAnimFrame = (function(callback) {
						        return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
						        function(callback) {
						          window.setTimeout(callback, 1000 / 60);
						        };
						      })();

						      function drawRectangle(myRectangle, context) {
						        context.beginPath();
						        context.rect(myRectangle.x, myRectangle.y, myRectangle.width, myRectangle.height);
						        context.fillStyle = '#8ED6FF';
						        context.fill();
						        context.lineWidth = myRectangle.borderWidth;
						        context.strokeStyle = 'black';
						        context.stroke();
						      }
						      function animate(myRectangle, canvas, context, startTime) {
						        // update
						        var time = (new Date()).getTime() - startTime;

						        var linearSpeed = 100;
						        // pixels / second
						        var newX = linearSpeed * time / 1000;

						        if(newX < canvas.width - myRectangle.width - myRectangle.borderWidth / 2) {
						          myRectangle.x = newX;
						        }

						        // clear
						        context.clearRect(0, 0, canvas.width, canvas.height);

						        drawRectangle(myRectangle, context);

						        // request new frame
						        requestAnimFrame(function() {
						          animate(myRectangle, canvas, context, startTime);
						        });
						      }
						      var canvas = document.getElementById('myCanvas');
						      var context = canvas.getContext('2d');

						      var myRectangle = {
						        x: 0,
						        y: 75,
						        width: 100,
						        height: 50,
						        borderWidth: 5
						      };

						      drawRectangle(myRectangle, context);

						      // wait one second before starting animation
						      setTimeout(function() {
						        var startTime = (new Date()).getTime();
						        animate(myRectangle, canvas, context, startTime);
						      }, 100);
						},

						dispose: function(){
							// document.body.removeChild(_container);
						},

						destroy: function(){

						}
					}

				});

				//Put it in the localStorage
				localStorage.egObj = JSON.stringify(enGameLoaded.toString());
		    }
		}

		//When user quit the page
		window.onbeforeunload = function (e) {
			//Store the date
			var nStartTime = Date.now();
			localStorage.setItem("egTime", nStartTime);

			if(enGameLoaded && enGameLoaded != null && enGameLoaded != undefined){
				enGameLoaded().draw();
			}
		};

	</script>

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
