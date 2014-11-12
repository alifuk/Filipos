<html>
<head>
	<title>Login</title>


	<meta charset="UTF-8">



</head>
<body>


	<?php

	if(isset( $_POST['heslo'] )   &&   $_POST['heslo'] == "budubohatej"){

		$_SESSION['prihlasen'] = "jop";
		echo "prihlasen"	;
		header("Location: admin.php");
	}
	else if (isset( $_POST['heslo'] )) {
		echo "Špatný heslo--";
		echo  $_POST['heslo'];


	}
	?>


	Heslo:
	<form action="login.php" method="post">
		<input type="password" name="heslo">
		<input type="submit" value="OK">
	</form>
</body>
</html>