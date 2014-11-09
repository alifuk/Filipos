<html>
<head>
	<title>Makeej</title>
	<link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
	<style type="text/css">
	ul{
		margin: 0;
	}
	li{
			list-style-type: none;
			border-right: 1px solid navy;
			border-color: #999;
	}
	#levyBlok{
		width: 200px;
		background-color: #FFF;
		padding: 10px;


		font: 9px/15px'EB Garamond', serif;
		text-transform: uppercase;

		letter-spacing: 3px;
		font-weight: 600;
		font-size: x-small;


		text-decoration: none;
		text-align: right;
	}



	#menu{
	}

	#nadpis{
		padding-right: 0px;
		padding-bottom: 12px;
	}


	#pravyBlok{
		width: 75%;
		float: right;
		background-color: #FFF;

	}


	@media only screen and (min-width: 768px){
		#levyBlok{

			width: 20%;
			padding: 30px;

			float: left;
			padding-top: 90px;
		}

		#pravyBlok{

			padding-top: 50px;
		}

		.thumb{
			max-width: 164px;
			max-height: 133px;
			padding: 12px;
			position: absolute;
    		top: 0;  
    		bottom: 0;  
    		left: 0;  
    		right: 0; 
    		margin:auto;
		}

		.thumbSpan{
			width: 164px;
			height: 133px;
			display: inline-block;
			position:relative;
		}

	}
	</style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>
<body >

	<div id="levyBlok">
		<div id="nadpis">
		Filip Švácha
		</div>

		<div id="menu">	
		<ul>

			<li>Já</li>
			<li>Fotím</li>
			<li>Kreslím</li>
			<li>Vytvářím</li>
			<li>Sportuji</li>
		
		

		</ul>
		</div>

	</div>

	<div id="pravyBlok">

		<?php


$dir = new RecursiveDirectoryIterator( "foto/voda/content/images/thumb" ,
    FilesystemIterator::SKIP_DOTS);

// Flatten the recursive iterator, folders come before their files
$it  = new RecursiveIteratorIterator($dir,
    RecursiveIteratorIterator::SELF_FIRST);

// Maximum depth is 1 level deeper than the base folder
$it->setMaxDepth(0);

// Basic loop displaying different messages based on file or folder
foreach ($it as $fileinfo) {
    if ($fileinfo->isFile() ) {
        /*printf("File From %s - %s\n", $it->getSubPath(), $fileinfo->getFilename());*/
        if ( strrpos($fileinfo->getFilename(), "jpg" ) ) {



            echo "
            <span class=\"thumbSpan\">
                <img class=\"thumb\" src=\" foto/voda/content/images/thumb/" . $fileinfo->getFilename() . " \" />
            </span>";





         
      }

    }
}


  ?>

	</div>




</body>
</html>