<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';

?>
<h2>Select League</h2>

<br>

Below is a list of all leagues that have been created. Select a league to enter match results. You can also edit a league's settings or <a href="addLeague.php">add new league</a>.

<br><br>

<table>
<form method="post"> 
 <?php

 //create and check connection
 require 'common/connectToDB.php';
 
 //define and run query of all leagues
 $sql = "SELECT id, name FROM leagues";
 $result = $conn->query($sql);
 
 if($result->num_rows > 0) {
	 //output the data of each row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr><td><button class=\"select-league\" type=\"submit\" formaction=\"enterMatch.php\" name=\"leagueid\" value=" . $row["id"] . ">" . $row["name"] . "</button></td>"; //creates links to each enterMatch for each league
		 echo "<td><button type=\"submit\" formaction=\"editLeague.php\" name=\"id\" value=" . $row["id"]. ">Edit</button></td></tr>"; //creates links to each editLeague for each league
	 }
 } else {
	 echo "No leagues found. Please create a league to continue.";
 }
 
 $conn->close();

 
?>
</form>
</table>
<br><br>
<a href="enterMatch.php"><button>Go Back</button></a>
</div>
</body>

</html>