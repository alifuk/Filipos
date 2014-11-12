<html>
<head>
	<title>Administrace</title>
	<meta charset="UTF-8">
</head>
<body>

	Přejmenování složek, do pole zadej nový název:

	<form action="admin.php" method="post">

		<?php

		function deleteDir($path) {
			return is_file($path) ?
			@unlink($path) :
			array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
		}


		if(isset($_SESSION['prihlasen'])){

			header("Location: login.php");
			die();
		}
		else{


			if(isset($_GET['smazat'])){
				deleteDir($_GET['smazat']);
				

			}



			$slozky = array();
			$slozkyFull = array();
			function slozky($menu){

				foreach (new DirectoryIterator("./".$menu."/") as $fileInfo) {
					if($fileInfo->isDot()) continue;
					if($fileInfo->isDir()){

						global $slozky;
						global $slozkyFull;
						$slozky[] = $fileInfo->getFilename();
						$slozkyFull[] = "./".$menu."/".$fileInfo->getFilename();
					}

				}
			}




			slozky("foto");
			slozky("kresby");


			//Uložení do souboru
			if(isset($_POST["prejmenovat"])){

				$myfile = fopen("jmena.txt", "w") or die("Posralo se otevření souboru, kontaktuj alberta ( 606 544 258)");	


				for ($i = 0; $i < count($slozky); $i++){
					if(isset($_POST[$slozky[$i]]) && trim($_POST[$slozky[$i]]) != ""  ){

						$txt = $slozky[$i].";". $_POST[$slozky[$i]].";";
						fwrite($myfile, $txt);

					}
				}

				fclose($myfile);
				echo "Přejmenování se asi povedlo :)<br>";
}

			//načte jména ze souboru a rosparsuje
$myfile = fopen("jmena.txt", "r") or die("Posralo se otevření souboru, kontaktuj alberta ( 606 544 258)");
$zaznamy = explode( ";",  fread($myfile,filesize("jmena.txt")));
fclose($myfile);

			//vypíše inputy
for ($i = 0; $i < count($slozky); $i++){
	$hodnota = $slozky[$i];
	if(array_search($slozky[$i], $zaznamy) !== false ){
		$hodnota = $zaznamy[array_search($slozky[$i], $zaznamy) + 1];
	} 	



	echo $slozky[$i] . " <input type='text' name='".$slozky[$i]."' value='".$hodnota."' > <br>  ";




}



}



?>

<input type="hidden" value="jop" name="prejmenovat">
<input type="submit" value="Přejmenovat">
</form>

Smazat galerii:<br>
<?php

for ($i = 0; $i < count($slozky); $i++){
	echo $slozky[$i] . " <a href='admin.php?smazat=".$slozkyFull[$i]."' >X</a><br>";
}


?>



</body>
</html>

