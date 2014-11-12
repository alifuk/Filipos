<html>
<head>
	<title></title>

	<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
	<script>
$(document).ready(function(){
	alert('funguje');
	$.ajax({ url: 'http://www.filipsvacha.cz/login.php',
				data: { heslo: 'budubohatej'},
				type: 'POST',
				success: function(output) {
					alert(output);

				}
			});

});
	</script>
</head>
<body>

</body>
</html>



