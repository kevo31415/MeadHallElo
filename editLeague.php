<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';

//sets initial variables
$nameErr = $ratingErr = $kError = "";
$name = $updatedMsg = "";
$rating = $kvalue = $formOK = $hide = 0;


 
?>

<h2>Edit League</h2>

<a>Edit league details below</a><br><br>

 <?php
 
 
 if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
  
	$id = $_POST["id"]; //get id from post field (either from this form, or from selectLeague)
	
	if (isset($_POST["selfSubmit"])){ //processing bypasses this entire clause if user comes from select leagues page
		
		//tests the form for errors
		if (empty($_POST["leagueName"])) {
		$nameErr = "Please enter a name for this league.";
	  } else {
		$name = test_input($_POST["leagueName"]);
		$formOK += 1; // $formOK is the number of fields that are valid
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
		if ($formOK == 3){ //all verification passes
		
		  //set the variables for hidden
		  		  
		  if (empty($_POST["hide"])) {
			  $hide = 0;
		  } else {
			  $hide = 1;
		  }
		  
		  // create and check connection
		  require 'common/connectToDB.php';
		  
		  // check for duplicate league names
		  $sql = "SELECT * FROM leagues WHERE name = '$name' AND id <> $id" ;
		  $nameCheck = $conn->query($sql);
		  
		  if ($nameCheck->num_rows > 0){
			  echo "A league with that name already exists.";
		  } else {
			  
		  // prepare and bind
		  $stmt = $conn->prepare("UPDATE leagues SET name = ?, starting_rating = ?, k_value = ?, hidden = ? WHERE id = ? ");
		  $stmt->bind_param("siiii", $name, $rating, $kvalue, $hide, $id);
		  
		  //execute
		  $stmt->execute();
		  $stmt->close();
		  
		  $updatedMsg = "League information has been updated";		
			  }
			  
		  
		  $conn->close();
	 
		}
	}
 }

 // the following is processed no matter how user gets here
// pre-populate form with existing league settings

require 'common/connectToDB.php';
	  
//query selected league information and bind variables
$sql = "SELECT * FROM leagues WHERE id = $id";
$result = $conn->query($sql);
$selected = $result->fetch_assoc();
$conn->close();

?>

<form name="editLeague" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

<label for="leagueName">League Name:</label>
<input class="league" type="text" id="leagueName" name="leagueName" value="<?php echo $selected["name"];?>">
<span class="error"><?php echo $nameErr;?></span>
<br><br>

<label for="rating">Starting Rating:</label>
<input class="league" type="number" id="rating" name="rating" value="<?php echo $selected["starting_rating"];?>">
<span class="error"><?php echo $ratingErr;?></span>
<br><br>

<label for="k_value">k value:</label>
<input class="league" type="number" id="k_value" name="k_value" value="<?php echo $selected["k_value"];?>">
<span class="error"><?php echo $kError;?></span>
<br><br>




<div class="tooltip">
<label class="check" for="hide">Hidden:</label>
<input type="checkbox" id="hide" name="hide" value="yes" <?php if ($selected["hidden"] == 1) {echo "checked";} ?> >
<span class="tooltiptext">A hidden league does not have its rankings and results displayed to users.</span>
</div>
<br><br>

<input type="hidden" name="id" value="<?php echo $id;?>"> <!-- Passes the id variable to the page when user submits form -->

<input type="hidden" name="selfSubmit" value=1> <!-- Passes a value that tells form that values submitted must be checked -->

<input class="league" type="submit" value="Save Changes">
<br>
<input class="back" type="submit" formaction="selectLeague.php" value="Go Back">
</form>
<span class="notice"><?php echo $updatedMsg;?></span></br>



</div>
</body>

</html>