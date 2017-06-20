<?php session_start();?>

<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';

$_SESSION["isLogged"] = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$saltString = $_POST["text"]."f320odfse4k4";
	if (md5($saltString) == "264a0cc785be879c1534829c011cbc55"){
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