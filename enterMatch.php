<html>

<body>
<div id="wrapper">
<?php require 'common/header.php';
class Player{
	public $id;
	public $name;
	public $league;
	public $rating;
	public $wins;
	public $losses;
	public $played;
	
	public function winMatch($deltaR){
		$this->rating += round($deltaR);
		$this->played += 1;
		$this->wins += 1;
	}
	
	public function loseMatch($deltaR){
		$this->rating -= round($deltaR);
		$this->played += 1;
		$this->losses += 1;
	}
	
	public function drawMatch($deltaR){ //this is the only function expected to take a negative value for deltaR
		$this->rating += round($deltaR);
		$this->played += 1;
	}
	
	//assigning values
	public function __construct(Array $properties=array()){
		foreach($properties as $key => $value) {
			$this->{$key} = $value;
		}
	}
	
	//return values
	public function info(){
		return "<h3>" . $this->name . "</h2>" .
		"Player id: " . $this->id . "<br>" .
		"League: " . $this->league . "<br>" .
		"Rating: " . $this->rating . "<br>" .
		"Wins: " . $this->wins . "<br>" .
		"Losses: " . $this->losses . "<br>" .
		"Matches played: " . $this->played . "<br>";
		
	}
	
}

function getExp(Player $a, Player $b) { //function returns probability of player A defeating player B
	$qA = pow(10, $a->rating / 400);
	$qB = pow(10, $b->rating / 400);
	$expA = $qA / ($qA + $qB); //note: expA + expB = 1
	return $expA; 
}

//intake variables
$leagueid = $_POST["leagueid"];

// set and reset variables
$dropdownHTML = $errorMsg = $newRankA = $newRankB = "";
$disableForm = false;

?>


<h2>Log Match Result</h2>
<a>Use the form on this page to log a new match result.</a><br><br>
<form name="navigate" method="post">
<button class="button-green" type="submit" formaction="selectLeague.php">Change League</button>   
<button class="button-yellow" type="submit" formaction="addPlayer.php" name="leagueid" value=<?php echo $leagueid; ?>>New Player</button>
</form>
<br><br>
<?php //used for testing 

echo $leagueid;?>

<form name="newMatch" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

<div class="playerSelect">
<div class="playerBox">

<?php

//create and check connection
require 'common/connectToDB.php';

//FLOW IF THERE IS A MATCH RESULT TRANSACTION TO PROCESS
if (isset($_POST["processResult"])) {
	
	//set specific variables	
	$playerAid = $_POST["playerA"];
	$playerBid = $_POST["playerB"];
	
	//test if same player selected
	if ($playerAid == $playerBid) {
		$errorMsg = "Error: Please select two different players to report a match between.";
	} else {
		
	// flow if two different players were selected
	
		//get k value
		$sql = "SELECT k_value FROM leagues where id = $leagueid";
		$result = $conn->query($sql)->fetch_assoc();
		$k = $result["k_value"];
		
		//get player information and cast each player as an object
		$sql = "SELECT * FROM players where id = $playerAid";
		$result = $conn->query($sql)->fetch_assoc();
		$playerA = new Player($result);
		
		$sql = "SELECT * FROM players where id = $playerBid";
		$result = $conn->query($sql)->fetch_assoc();
		$playerB = new Player($result);
		
		//make ranking calculations based on which player won
		switch ($_POST["resultSelect"]){
			case "Player A Win":
				$exp = getExp($playerA, $playerB);
				$deltaR = $k * (1 - $exp);
				$playerA->winMatch($deltaR);
				$playerB->loseMatch($deltaR);
				break;
				
			case "Player B Win":
				$exp = getExp($playerB, $playerA);
				$deltaR = $k * (1 - $expA);
				$playerA->winMatch($deltaR);
				$playerB->loseMatch($deltaR);
				break;
			
			case "Draw":
				$expA = getExp($playerA, $playerB);
				$deltaR = $k * (0.5 - $expA);
				$playerA->drawMatch($deltaR);
				$playerB->drawMatch(-1 * $deltaR);
				
				break;	
		}
		
		//update player ratings to DB
		// prepare and bind for player A
		$stmt = $conn->prepare("UPDATE players SET rating = ?, wins = ?, losses = ?, played = ? WHERE id = ? ");
		$stmt->bind_param("iiiii", $playerA->rating, $playerA->wins, $playerA->losses, $playerA->played, $playerA->id);
		
		$stmt->execute();
		$stmt->close();
		
		$newRankA = "New Rating: " . $playerA->rating;
		
		// prepare and bind for player B
		$stmt = $conn->prepare("UPDATE players SET rating = ?, wins = ?, losses = ?, played = ? WHERE id = ? ");
		$stmt->bind_param("iiiii", $playerB->rating, $playerB->wins, $playerB->losses, $playerB->played, $playerB->id);
		
		$stmt->execute();
		$stmt->close();
		
		$newRankB = "New Rating: " . $playerB->rating;
		
		//insert row into match screen
	}
}

 
//define and run query of all players in this league
$sql = "SELECT id, name FROM players WHERE league=$leagueid";
$result = $conn->query($sql);

//populate the dropdown to select player A
if($result->num_rows > 1) {
	//create HTML to make dropdown, populating each row with a player's name and ID and storing the whole thing as a string
	while($row = $result->fetch_assoc()) {
		$dropdownHTML .= "<option value=\"" . $row["id"] . "\">" . $row["name"] . "</option>";
	}
	echo "Player A <br>";
	echo "<select id=\"playerA\" name=\"playerA\" class=\"select-center\">" . $dropdownHTML . "</select>"; //echo that HTML to make the dropdown
}  else {
	echo "Not enough players are in this league.<br>Please use the options above to add new players.";
	$disableForm = true; //removes the submission buttons
}

?>
<br>
<span class="matchMsg"><?php echo $newRankA; ?></span><span class="winMatch"> (+420)</span>
</div>
<div class="playerBox">
<?php 
//populate the dropdown to select player B
if($result->num_rows > 0) {
	echo "Player B <br>";
	echo "<select id=\"playerB\" name=\"playerB\" class=\"select-center\">" . $dropdownHTML . "</select>"; //since P1 and P2 dropdowns are identical, just echo original variable
} else {
	echo "<br><br>";
}  

?>
<br>
<?php echo $newRankB; ?><span class="loseMatch">-69</a>
</div>

<input type="hidden" name="leagueid" value="<?php echo $leagueid;?>"> <!-- Passes the value of the selected league  -->

<input type="hidden" name="processResult" value=1> <!-- This value tells form that this is NOT the first time on page i.e. there is a result to process -->

</div>

<!-- This javascript automatically selects the 2 players from the last match -->
<script type="text/javascript"> 
var lastPlayerA = <?php echo $playerAid;?>;
var lastPlayerB = <?php echo $playerBid;?>;

document.getElementById('playerA').value = lastPlayerA;
document.getElementById('playerB').value = lastPlayerB;


</script>

<div id="buttons" <?php if($disableForm){echo "style=\"display:none;\"";}?>>
<input class="button-a" name="resultSelect" type="submit" value="Player A Win">
<input class="button-a" name="resultSelect" type="submit" value="Draw">
<input class="button-a" name="resultSelect" type="submit" value="Player B Win">
</div>
<br>
<span class="error"><?php echo $errorMsg;?></span>
</form>

</body>

</html>