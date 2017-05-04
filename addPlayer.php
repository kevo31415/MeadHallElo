<html>
<head>

</head>
<body>
<div id="wrapper">
<?php require 'common/header.php';

function getLeagueNameById($leagueid) {
	require 'common/connectToDB.php';
	$sql = "SELECT name FROM leagues WHERE id='$leagueid'";
	$result = $conn->query($sql)->fetch_assoc();
	return $result["name"];
	$conn->close();
}

//reset variables
$nameErr = $name = $playerAddedMsg = $leagueName = "";
$rating = 0;

//intake variables
$leagueid = $_POST["leagueid"];



if (isset($_POST["submit"])) {
	
	//make sure name field is not blank
  if (empty($_POST["playerName"])) {
    $nameErr = "Please enter a name for this player.";
	
  } else {
    	
	//flow if valid name is provided
	//create and check connection
	require 'common/connectToDB.php';
	
	//define and run query of league with selected id
	
	$name = test_input($_POST["playerName"]);
	
	//check for duplicate players within same league
	$sql = "SELECT * FROM players WHERE name = '$name' AND league = '$leagueid'";
	$nameCheck = $conn->query($sql);
	if ($nameCheck->num_rows > 0){
		  $nameErr = "A player with that name already exists in this league.";
	  } else {
		 
		//valid name and no duplicate
		$sql = "SELECT * FROM leagues WHERE id = '$leagueid'";
		$result = $conn->query($sql);
	
		 //get value of league starting rating
		$row = $result->fetch_assoc();
		$rating = $row["starting_rating"];
		$leagueName = $row["name"];
		
		//prepare and bind
		$stmt = $conn->prepare("INSERT INTO players (name, league, rating, wins, losses, played) VALUE(?, ?, ?, 0, 0, 0)");
		$stmt->bind_param("sii", $name, $leagueid, $rating);
		
		//execute
		$stmt->execute();
		$stmt->close();
	
		//create confirmation message
		$playerAddedMsg = "Player " . $name . " has been added to the league " . $leagueName;
	  }
		
	$conn->close();
  }
} 


?>

<h2>Add New Player</h2>
<a>To add a player to <?php echo getLeagueNameById($leagueid);?>, enter their name below.</a><br><br>

<form name="newPlayer" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<label for="playerName">Player Name:</label>
<input class="player" type="text" id="playerName" name="playerName">
<span class="error"><?php echo $nameErr;?></span>
<br><br>

<input type="hidden" name="leagueid" value="<?php echo $leagueid;?>"> <!-- Passes the value of the selected league  -->

<input class="player" type="submit" name="submit" value="Add New Player">
<br>
<button class="other" type="submit" formaction="enterMatch.php" name="leagueid" value=<?php echo $leagueid; ?>>Go Back</button>
</form>

<br>
<?php echo $playerAddedMsg; ?>

</div>
</body>
</html>