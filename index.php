<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';

?>
<h2>Home Page</h2>
<br>
<p>
Welcome to the Mead Hall Elo rating site. Here you can check player Elo ratings for selected competitions at Mead Hall. Select a league to view below.
</p>

<table>
<form method="post">
 <?php

 //create and check connection
 require 'common/connectToDB.php';
 
 //define and run query of all leagues
 $sql = "SELECT leagues.id, leagues.name, count(*) AS 'player_count' FROM leagues, players WHERE hidden = 0 AND leagues.id = players.league GROUP BY leagues.id";
 $result = $conn->query($sql);
 
 
 if($result->num_rows > 0) {
	 echo "<tr><th>League Name</th><th>Players</th></tr>";
	 //output the data of each row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr><td><button class=\"select-league\" type=\"submit\" formaction=\"viewLeague.php\" name=\"leagueid\" value=" . $row["id"] . ">" . $row["name"] . "</button></td>"; //creates links to each league rankings page
		 echo "<td>" . $row["player_count"] . "</td></tr>"; //prints the number of players in each row
	 }
 } else {
	 echo "No leagues found. Please have your TO set up a league to continue.";
 }
 
 $conn->close();

 
?>
</form>
</table>
<p>
<a href="logon.php">Admin sign-in</a>
</p>
</div>
</body>

</html>