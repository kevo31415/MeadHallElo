<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';

//intake variables
$leagueid = $_POST["leagueid"];

//get league name
require 'common/connectToDB.php';

$sql = "SELECT name FROM leagues where id = $leagueid";
$result = $conn->query($sql)->fetch_assoc();
$leagueName = $result["name"];

echo "<h2>$leagueName Rankings</h2>"
?>


<br><br>

<table>
<form method="post"> 
 <?php
 
 //define and run query of players in this league
 $sql = "SELECT * FROM players WHERE league = $leagueid ORDER BY rating DESC";
 $result = $conn->query($sql);
 
 //echo var_dump($result->fetch_assoc());
 
 if($result->num_rows > 0) {
	 echo "<tr><th class=\"left\">Player Name</th><th>Rating</th><th>Wins</th><th>Losses</th><th>Played</th></tr>"; //table header
	 //output the data of each row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr><td class=\"left\"><button class=\"select-player\" type=\"submit\" formaction=\"viewPlayer.php\" name=\"id\" value=" . $row["id"] . ">" . $row["name"] . "</button></td>"; //player name and post to player page
		 echo "<td>" . $row["rating"] . "</td>"; //player rating
		 echo "<td>" . $row["wins"] . "</td>"; //player wins
		 echo "<td>" . $row["losses"] . "</td>"; //player losses
		 echo "<td>" . $row["played"] . "</td>"; //player matches played
		 echo "</tr>";
	 }
 } else {
	 echo "No players found for this league.";
 }
 
 $conn->close();

 
?>
</form>
</table>
<br>

<form name="navigate" method="post" action="homePage.php">

<button class="other" type="submit">Go Back</button>

</form>

</div>
</body>

</html>