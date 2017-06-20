<html>

<head>

<link rel="stylesheet" type="text/css" href="common/stylesheet.css">


</head>

<body>
	<div id="header">
		<a href="index.php"><img src="images/meadhall_logo.png" height="200"></a>
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
 
 // all information about a specific player
 class Player{
	public $id;
	public $name;
	public $league;
	public $rating;
	public $wins;
	public $losses;
	public $played;
	
	public function winMatch($deltaR){
		$this->rating += $deltaR;
		$this->played += 1;
		$this->wins += 1;
	}
	
	public function loseMatch($deltaR){
		$this->rating -= $deltaR;
		$this->played += 1;
		$this->losses += 1;
	}
	
	public function drawMatch($deltaR){ //this is the only function expected to take a negative value for deltaR
		$this->rating += $deltaR;
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

function leagueNameByid($x){
	//create and check connection
	require 'common/connectToDB.php';
	
	$sql = "select name FROM leagues WHERE id = $x";
	$result = $conn->query($sql)->fetch_assoc();
	return $result["name"];
	$conn->close();
	
}


 
?>

</html>
