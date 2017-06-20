<?php session_start();?>

<html>
<head>

</head>
<body>
<div id="wrapper">

<?php
require 'common/admin.php';

//tests the form for errors
$nameErr = $ratingErr = $kError = $leagueCreated = "";
$name = "";
$rating = $kvalue = $formOK = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["leagueName"])) {
    $nameErr = "Please enter a name for this league.";
  } else {
    $name = test_input($_POST["leagueName"]);
	$formOK += 1;
  }
  
  if ($_POST["rating"] < 1) {
	  $ratingErr = "The starting rating must be a number greater than 1.";
  } else {
	  $rating = test_input($_POST["rating"]);
	  $formOK += 1;
  }
  
  if ($_POST["k_value"] < 1) {
	  $kError = "The k value must be a number greater than 1";
  } else {
	  $kvalue = test_input($_POST["k_value"]);
	  $formOK += 1;
  }
  
  // $formOK is the number of fields that are valid
//all verification passes
  if ($formOK == 3){
	  // create and check connection
	  require 'common/connectToDB.php';
	  
	  // check for duplicate league names
	  $sql = "SELECT * FROM leagues WHERE name = '$name'";
	  $nameCheck = $conn->query($sql);
	  
	  if ($nameCheck->num_rows > 0){
		  $nameErr = "A league with that name already exists.";
	  } else {
	  // prepare and bind
	  $stmt = $conn->prepare("INSERT INTO leagues (name, starting_rating, k_value) VALUE(?, ?, ?)");
	  $stmt->bind_param("sii", $name, $rating, $kvalue);
	  
	  //execute
	  $stmt->execute();
	  $stmt->close();
	  
	  $leagueCreated = "<strong>New league created successfully</strong> <br>" . $name . 
	  "<br>Starting Elo rating: " . $rating . 
	  "<br>K value: " . $kvalue;
	  
	  	  }
		  
	  
	  $conn->close();
  }
 }

 
?>

<h2>Add New League</h2>
<p>Please fill out the following form to add a new league.</p><br>

<form name="newLeague" method="post">
<label for="leagueName">League Name:</label>
<input class="league" type="text" id="leagueName" name="leagueName">
<span class="error"><?php echo $nameErr;?></span>
<br><br>

<label for="rating">Starting Rating:</label>
<input class="league" type="number" id="rating" name="rating" value=1600>
<span class="error"><?php echo $ratingErr;?></span>
<br><br>

<label for="k_value">k value:</label>
<input class="league" type="number" id="k_value" name="k_value" value=20>
<span class="error"><?php echo $kError;?></span>
<br><br>

<input class="league" type="submit" formaction="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" value="Create League">
<br>
<input class="other" type="submit" formaction="selectLeague.php" value="Go Back">
</form>

<p><?php echo $leagueCreated; ?></p>


</div>
</body>

</html>