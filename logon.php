<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';

//echo md5("testingf320odfse4k4");
session_start();
$_SESSION["isLogged"] = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$saltString = $_POST["text"]."f320odfse4k4";
	if (md5($saltString) == "198e39b84a4982360cec5dc1bd9d05d1"){
		$_SESSION["isLogged"] = true;
	}
}


?>
<h2>Admin Log In</h2>

<form name="logon" method="post" <?php if ($_SESSION['isLogged']) {echo "hidden ";}?>>

<label for="password">Password:</label>
<input class="player" id="text" name="text">

<input class="player" type="submit" name="submit" value="Submit" formaction="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

</form>

<?php

if ($_SESSION["isLogged"]) {
	echo "Password Ok <br> <a href=\"selectLeague.php\">Continue</a>";
}

?>


</table>

</div>
</body>

</html>