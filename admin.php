<html>
<head>
	<title>Administrace</title>
	<meta charset="UTF-8">
</head>
<body>

	Přejmenování složek:

	<form action="admin.php" method="post">

		<?php
		if(isset($_SESSION['prihlasen'])){

			header("Location: login.php");
			die();
		}
		else{

			$slozky = array();
			function slozky($menu){

				foreach (new DirectoryIterator("./".$menu."/") as $fileInfo) {
					if($fileInfo->isDot()) continue;
					if($fileInfo->isDir()){

						global $slozky;
						$slozky[] = $fileInfo->getFilename();

					}

				}
			}




			slozky("foto");
			slozky("kresby");



			if(isset($_POST["prejmenovat"])){

				$myfile = fopen("jmena.txt", "w") or die("Posralo se otevření souboru, kontaktuj alberta ( 606 544 258)");
				


				for ($i = 0; $i < count($slozky); $i++){
					if(isset($_POST[$slozky[$i]]) && trim($_POST[$slozky[$i]]) != ""  ){



						$txt = $slozky[$i].";". $_POST[$slozky[$i]].";";
						fwrite($myfile, $txt);

					}
				}



				fclose($myfile);
			}


			$myfile = fopen("jmena.txt", "r") or die("Posralo se otevření souboru, kontaktuj alberta ( 606 544 258)");
			$zaznamy = explode( ";",  fread($myfile,filesize("jmena.txt")));
			fclose($myfile);

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




</body>
</html>

