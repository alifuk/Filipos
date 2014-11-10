<?php
if(isset($_POST['action']) && !empty($_POST['action']) && isset($_POST['menu']) && !empty($_POST['menu'])    ) {
	$action = $_POST['action'];
	$menu = $_POST['menu'];



	foreach (new DirectoryIterator("./".$menu . "/" .  $action . "/content/bin/images/thumb") as $fileInfo) {
		if($fileInfo->isDot()) continue;
		if($fileInfo->isFile()){

			echo "
			<span class=\"thumbSpan\">
			<img class=\"thumb\" src=\"./".$menu . "/" .  $action . "/content/bin/images/thumb/" . $fileInfo->getFilename() . " \" cesta=\"./".$menu . "/" .  $action . "/content/bin/images/thumb\"/>
			</span>";


		}

	}
}
?>