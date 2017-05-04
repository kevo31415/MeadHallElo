<html>

<head>

<link rel="stylesheet" type="text/css" href="common/stylesheet.css">


</head>

<body>
	<div id="header">
		<a href="homePage.php"><img src="images/meadhall_logo.png" height="200"></a>
		<br>
		<h1>Mead Hall Elo Tracker</h1>
	</div>
</body>

<?php
// strips illegal characters from input fields
 function test_input($data) {
	$illegal_chars = array("'", "\"", "$", "&quot;");
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	$data = str_replace($illegal_chars, "", $data);
	return $data;
 }
 
?>

</html>
