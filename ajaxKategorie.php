<?php
if(isset($_POST['menu']) && !empty($_POST['menu']) ) {
	$slozka = $_POST['menu'];

	$slozky = array();


	foreach (new DirectoryIterator("./".$slozka."/") as $fileInfo) {
		if($fileInfo->isDot()) continue;
		if($fileInfo->isDir()){

			global $slozky;
			$slozky[] = "./".$slozka."/" . $fileInfo->getFilename();

		}

	}



	for($i = 0; $i < count($slozky); $i++){

		foreach (new DirectoryIterator($slozky[$i]."/content/bin/images/thumb") as $fileInfo) {
			if($fileInfo->isDot()) continue;
			if($fileInfo->isFile()){

				$fotky[] = $slozky[$i]."/content/bin/images/thumb/".$fileInfo->getFilename();

			}

		}

	}


	shuffle($fotky);

	for ($i = 0; $i < count($fotky); $i++){


		echo "
		<span class=\"thumbSpan\">
		<img class=\"thumb\" src=\"./".$fotky[$i]."\" cesta=\"./".$fotky[$i]."\"/>
		</span>";





	}

	


	
}
?>