<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';
require 'common/connectToDB.php';
$sql = "SELECT id, name FROM players WHERE league = 1";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()){
	$playerNames[$row["id"]] = $row["name"];
}


?>
</div>
</body>
</html>