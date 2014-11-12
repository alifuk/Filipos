<html>
<head>
	<title>Filip Švácha</title>
	<link href='http://fonts.googleapis.com/css?family=Play:400,700' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="./js/jssor.js"></script>
	<script type="text/javascript" src="./js/jssor.slider.js"></script>
	<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
	<meta name="viewport" content="width=device-width">
	<meta charset="UTF-8">
	<style type="text/css">
	ul{
		margin: 0;
	}
	li{
		list-style-type: none;
		border-right: 1px solid navy;
		border-color: #999;
	}
	a {
		text-decoration: none;
		color: #000;
	}

	

	#levyBlok{
		width: 90%;
		background-color: #FFF;
		padding: 10px;


		font: 9px/15px'Play', sans-serif;
		text-transform: uppercase;

		letter-spacing: 3px;
		font-weight: 400;
		font-size: x-small;


		text-decoration: none;
		text-align: right;
	}



	#menu{
		cursor: pointer;
	}

	#nadpis{
		padding-right: 0px;
		padding-bottom: 12px;
		font-weight: 800;
		font-weight: bolder;
	}

	#fotimSpan, #kreslimSpan, #sportujiSpan{
		padding: 5px 0;
	}

	.fotim , .kreslim, .sportuji{
		/*font-style: italic;*/
		border: none;
		padding-right: 20px;
	}

	#ja{
		padding: 50px;
	}


	#pravyBlok{
		clear: both;
		width: 100%;
		background-color: #FFF;

		font: 21px/25px'EB Garamond', serif;

	}

	.thumb{

		position: absolute;
		display: block;
		top:0;
		right:0;
		bottom: 0;
		left:0;
		margin: auto;	
		cursor: pointer;
	}

	.thumbSpan{
		display: inline-block;
		width: 49%;
		height: 206px;

		position: relative;
	}


	@media only screen and (min-width: 768px){
		#levyBlok{

			width: 20%;
			padding: 30px;

			float: left;
			padding-top: 90px;
		}

		#pravyBlok{
			clear: none;
			width: 75%;
			float: right;
			padding-top: 30px;
		}

		.thumb{
		}

		.thumbSpan{
			width: 214px;
		}


	}
	</style>
	<script>
	$(document).ready(function(){

		var skrytoFotim = 1;
		var skrytoKreslim = 1;
		hideAll();

		$.ajaxSetup({ cache: false });



		
		//menu skrývání a otevírání položek
		function hideAll() {
			$("#fotimSpan").hide();
			$("#kreslimSpan").hide();
			$("#sportujiSpan").hide();     
			skrytoFotim = 1;
			skrytoKreslim = 1;





		}



		function menuClick(nazev, statusSkryti){

			if(statusSkryti == 1){
				hideAll();
				$(nazev+"Span").show(100);
				statusSkryti = 0;
			}
			else{
				hideAll();
				statusSkryti = 1;
			}

		}



		$("#fotimMenu").click(function(){
			menuClick("#fotim", skrytoFotim);
		});

		$("#kreslimMenu").click(function(){
			menuClick("#kreslim", skrytoFotim);
		});










		$("#menuJa").click(function(){

			$("#pravyBlok").fadeOut(300);

			setTimeout(function() {
				$("#pravyBlok").html("Hej,<br>jmenuji se Filip Švácha,<br>a fotím,<br>a kreslím,<br>každý den,<br>a tohle je výběr mé tvorby.<br><br>Email: filipsvacha@gmail.com");
				$("#pravyBlok").fadeIn(300);
			}, 500);

			







		});



		$(".fotim").click(function(){
			$("#pravyBlok").fadeOut(500);
			var hotovoOut = 0;
			var hotovoIn = 0;
			var kod = "a";


			setTimeout(function() {
      	// Do something after 5 seconds
      	if(hotovoIn == 1){
      		$("#pravyBlok").html(kod);
      		$("#pravyBlok").fadeIn(300);
      	}
      }, 500);


			$.ajax({ url: 'ajax.php',
				data: {action: $(this).attr("data"), menu: 'foto'},
				type: 'POST',
				success: function(output) {
					/*alert(output);*/
					if(hotovoOut == 1){
						$("#pravyBlok").html(output);
						$("#pravyBlok").fadeIn(300);

					}
					else{
						hotovoIn = 1;
						kod = output;
					}

				}
			});


		});


		$(".kreslim").click(function(){
			$("#pravyBlok").fadeOut(500);
			var hotovoOut = 0; //pokud je hotovo skrývání
			var hotovoIn = 0;	//pokud je ajax načtený
			var kod = "a"; // sem se uloží kod z ajaxu


			setTimeout(function() {
				if(hotovoIn == 1){
					$("#pravyBlok").html(kod);
					$("#pravyBlok").fadeIn(300);
				}
			}, 500);


			$.ajax({ url: 'ajax.php',
				data: {action: $(this).attr("data"), menu: 'kresby'},
				type: 'POST',
				success: function(output) {
					if(hotovoOut == 1){
						$("#pravyBlok").html(output);
						$("#pravyBlok").fadeIn(300);

					}
					else{
						hotovoIn = 1;
						kod = output;
					}

				}
			});


		});




//kliknutí na náhled

$("#pravyBlok").on("click",".thumb", function(){



	$("#pravyBlok").fadeOut(500);
			var hotovoOut = 0; //pokud je hotovo skrývání
			var hotovoIn = 0;	//pokud je ajax načtený
			var kod = "a"; // sem se uloží kod z ajaxu


			setTimeout(function() {
				if(hotovoIn == 1){
					$("#pravyBlok").html(kod);
					$("#pravyBlok").fadeIn(300);
					jssor_slider1.$GoTo(2); 
				}
			}, 500);


			$.ajax({ url: 'ajaxGalerie.php',
				data: {name: $(this).attr("src"), cesta: $(this).attr("cesta"), menu: 'sport'},
				type: 'POST',
				success: function(output) {					
					if(hotovoOut == 1){
						$("#pravyBlok").html(output);
						$("#pravyBlok").fadeIn(300);

					}
					else{
						hotovoIn = 1;
						kod = output;
					}

				}
			});
		});






});
</script>

</head>
<body >

	<div id="levyBlok">
		<div id="nadpis">
			Filip Švácha
		</div>

		<div id="menu">	
			<ul>

				<li id="menuJa">Já</li>
				<li id="fotimMenu">Fotím</li>
				<span id="fotimSpan">
					<?php

					$myfile = fopen("jmena.txt", "r") or die("Posralo se otevření souboru, kontaktuj alberta ( 606 544 258)");
					$zaznamy = explode( ";",  fread($myfile,filesize("jmena.txt")));
					fclose($myfile);



					foreach (new DirectoryIterator('./foto') as $fileInfo) {
						if($fileInfo->isDot()) continue;
						if($fileInfo->isDir()){


							$hodnota = utf8_decode($fileInfo->getFilename()); //jméno jak to je v souborovém systému
							if(array_search($hodnota, $zaznamy) !== false ){
								$hodnota = $zaznamy[array_search($hodnota, $zaznamy) + 1];
							} 


							echo "<li class=\"fotim\" data='".utf8_decode($fileInfo->getFilename())."'>".$hodnota . "</li>";

						}

					}
					?>

				</span>
				<li id="kreslimMenu">Kreslím</li>
				<span id="kreslimSpan">
					<?php

					$myfile = fopen("jmena.txt", "r") or die("Posralo se otevření souboru, kontaktuj alberta ( 606 544 258)");
					$zaznamy = explode( ";",  fread($myfile,filesize("jmena.txt")));
					fclose($myfile);



					foreach (new DirectoryIterator('./kresby') as $fileInfo) {
						if($fileInfo->isDot()) continue;
						if($fileInfo->isDir()){


							$hodnota = utf8_decode($fileInfo->getFilename()); //jméno jak to je v souborovém systému
							if(array_search($hodnota, $zaznamy) !== false ){
								$hodnota = $zaznamy[array_search($hodnota, $zaznamy) + 1];
							} 


							echo "<li class=\"kreslim\" data='".utf8_decode($fileInfo->getFilename())."'>".$hodnota . "</li>";

						}

					}
					?>
				</span>
				<li><a href="http://daydreamingphotoboy.tumblr.com/">Blog</a></li>

			</span>



		</ul>
	</div>


	<div id="ja">
		Hej,<br>jmenuji se Filip Švácha,<br>a fotím,<br>a kreslím,<br>každý den,<br>a tohle je výběr mé tvorby.<br><br>Email: filipsvacha@gmail.com



	</div>


</div>

<div id="pravyBlok">



	<script>

	jQuery(document).ready(function ($) {

		var _SlideshowTransitions = [
            //Shift left
            { $Duration: 1200, x: 1, $Easing: { $Left: $JssorEasing$.$EaseInOutQuart },  $Brother: { $Duration: 1200, x: -1, $Easing: { $Left: $JssorEasing$.$EaseInOutQuart } } },
            ];

            var options = {
            	$FillMode: 1,
            	$LazyLoading: 2,
                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
                $AutoPlayInterval: 3000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 1,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                $SlideDuration: 400,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
                $SlideWidth: 1000,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                $SlideHeight: 600,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                $SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
                $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
                $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                $DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

                $SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
                    $Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
                    $Transitions: _SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
                    $TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
                    $ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
                },

                $BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
                    $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
                    $ChanceToShow: 0,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                    $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
                    $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
                    $SpacingX: 10,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
                    $SpacingY: 10,                                   //[Optional] Vertical space between each item in pixel, default value is 0
                    $Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
                },

                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
                    $ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
                }
            };
            var jssor_slider1 = new $JssorSlider$("slider1_container", options);

            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizes
            function ScaleSlider() {
            	var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
            	if (parentWidth)
            		jssor_slider1.$ScaleWidth(Math.min(parentWidth, 1000));
            	else
            		window.setTimeout(ScaleSlider, 30);
            }

            ScaleSlider();

            if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
            	$(window).bind('resize', ScaleSlider);
            }


            //if (navigator.userAgent.match(/(iPhone|iPod|iPad)/)) {
            //    $(window).bind("orientationchange", ScaleSlider);
            //}
            //responsive code end
        });
</script>
<!-- Jssor Slider Begin -->
<!-- You can move inline styles to css file or css block. -->
<div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 1000px; height: 600px; overflow: hidden; ">

	<!-- Loading Screen -->
	<div u="loading" style="position: absolute; top: 0px; left: 0px;">
		<div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
		background-color: #000000; top: 0px; left: 0px;width: 100%;height:100%;">
	</div>
	<div style="position: absolute; display: block; background: url(./img/loading.gif) no-repeat center center;
	top: 0px; left: 0px;width: 100%;height:100%;">
</div>
</div>

<!-- Slides Container -->
<div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1000px; height: 600px; overflow: hidden;">
	<!--
	<div>
		<img u="image" src="../img/landscape/11.jpg" />
	</div>
-->

<?php


$slozky = array();
function slozky($menu){

	foreach (new DirectoryIterator("./".$menu."/") as $fileInfo) {
		if($fileInfo->isDot()) continue;
		if($fileInfo->isDir()){

			global $slozky;
			$slozky[] = "./".$menu."/" . $fileInfo->getFilename();

		}

	}
}




slozky("foto");
slozky("kresby");


for($i = 0; $i < count($slozky); $i++){

	foreach (new DirectoryIterator($slozky[$i]."/content/bin/images/large") as $fileInfo) {
		if($fileInfo->isDot()) continue;
		if($fileInfo->isFile()){

			$fotky[] = $slozky[$i]."/content/bin/images/large/".$fileInfo->getFilename();

		}

	}

}


shuffle($fotky);

for ($i = 0; $i < count($fotky); $i++){


	echo "<div>
	<img u=\"image\" src2=\"image\" src=\"". $fotky[$i] . " \" />
	</div>";




}



?>

</div>

<!-- Bullet Navigator Skin Begin -->
<style>
/* jssor slider bullet navigator skin 05 css */
            /*
            .jssorb05 div           (normal)
            .jssorb05 div:hover     (normal mouseover)
            .jssorb05 .av           (active)
            .jssorb05 .av:hover     (active mouseover)
            .jssorb05 .dn           (mousedown)
            */
            .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
            	background: url(./img/b05.png) no-repeat;
            	overflow: hidden;
            	cursor: pointer;
            }

            .jssorb05 div {
            	background-position: -7px -7px;
            }

            .jssorb05 div:hover, .jssorb05 .av:hover {
            	background-position: -37px -7px;
            }

            .jssorb05 .av {
            	background-position: -67px -7px;
            }

            .jssorb05 .dn, .jssorb05 .dn:hover {
            	background-position: -97px -7px;
            }
            </style>
            <!-- bullet navigator container -->
            <div u="navigator" class="jssorb05" style="position: absolute; bottom: 16px; right: 6px;">
            	<!-- bullet navigator item prototype -->
            	<div u="prototype" style="POSITION: absolute; WIDTH: 16px; HEIGHT: 16px;"></div>
            </div>
            <!-- Bullet Navigator Skin End -->
            <!-- Arrow Navigator Skin Begin -->
            <style>
            /* jssor slider arrow navigator skin 12 css */
            /*
            .jssora12l              (normal)
            .jssora12r              (normal)
            .jssora12l:hover        (normal mouseover)
            .jssora12r:hover        (normal mouseover)
            .jssora12ldn            (mousedown)
            .jssora12rdn            (mousedown)
            */
            .jssora12l, .jssora12r, .jssora12ldn, .jssora12rdn {
            	position: absolute;
            	cursor: pointer;
            	display: block;
            	background: url(./img/a15.png) no-repeat;
            	overflow: hidden;
            }

            .jssora12l {
            	background-position: -16px -37px;
            }

            .jssora12r {
            	background-position: -75px -37px;
            }

            .jssora12l:hover {
            	background-position: -136px -37px;
            }

            .jssora12r:hover {
            	background-position: -195px -37px;
            }

            .jssora12ldn {
            	background-position: -256px -37px;
            }

            .jssora12rdn {
            	background-position: -315px -37px;
            }
            </style>
            <!-- Arrow Left -->
            <span u="arrowleft" class="jssora12l" style="width: 30px; height: 46px; top: 275px; left: 0px;">
            </span>
            <!-- Arrow Right -->
            <span u="arrowright" class="jssora12r" style="width: 30px; height: 46px; top: 275px; right: 0px">
            </span>
            <!-- Arrow Navigator Skin End -->
            <a style="display: none" href="http://www.jssor.com">slideshow</a>
        </div>





    </div>




</body>
</html>