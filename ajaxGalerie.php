<?php
if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['menu']) && !empty($_POST['menu']) && isset($_POST['cesta']) && !empty($_POST['cesta'])    ) {
    $name = $_POST['name'];
    $menu = $_POST['menu'];
    $cesta = $_POST['cesta'];


    $name = str_replace("thumb","large",$name);
    $cesta = str_replace("thumb","large",$cesta);
    $name = str_replace(" ","",$name);
    $cesta = str_replace(" ","",$cesta);
    $index =0;
    $potrebnyIndex = 5;

    foreach (new DirectoryIterator($cesta) as $fileInfo) {
        if($fileInfo->isDot()) continue;
        if($fileInfo->isFile()){


            if(strcmp((string)($cesta ."/". $fileInfo->getFilename()),(string)($name)) == 0){
                $potrebnyIndex = $index;
            }


        }
        $index++;
    }


}

?>



<script>

jQuery(document).ready(function ($) {

    var _SlideshowTransitions = [
            //Shift left
            { $Duration: 1200, x: 1, $Easing: { $Left: $JssorEasing$.$EaseInOutQuart },  $Brother: { $Duration: 1200, x: -1, $Easing: { $Left: $JssorEasing$.$EaseInOutQuart } } },
            ];

            var options = {
                $FillMode: 1,
                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
                $AutoPlayInterval: 3000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 1,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1

                $ArrowKeyNavigation: true,                          //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                $SlideDuration: 400,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
                $SlideWidth: 1000,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                $SlideHeight: 600,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                $SlideSpacing: 0,                                   //[Optional] Space between each slide in pixels, default value is 0
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

            jssor_slider1.$GoTo(<?php echo $potrebnyIndex ?>); 

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
foreach (new DirectoryIterator($cesta) as $fileInfo) {
    if($fileInfo->isDot()) continue;
    if($fileInfo->isFile()){


        echo "<div>
        <img u=\"image\" src=\"". $cesta ."/". $fileInfo->getFilename() . " \" />
        </div>";


    }

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















