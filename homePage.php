<html>
<head>
<style>
<!-- yeah I'm using an internal stylesheet for the home page, bite me -->

a {text-decoration: none;}

#rankings {
	width: 50%;
	font-weight: bold;
	text-align: center;
    padding: 14px 20px;
    margin: 3px auto;
	font-size: 1.1em;
	border: 3px solid;
	border-radius: 3px;
    cursor: pointer;
	background-color: white;
	color: #a10302;
	border-color: #a10302;
}

#rankings:hover {
	background-color: #a10302;
	color: white;
}

#admin {
	width: 50%;
	font-weight: bold;
	text-align: center;
    padding: 14px 20px;
    margin: 3px auto;
	font-size: 1.1em;
	border: 3px solid;
	border-radius: 3px;
    cursor: pointer;
	background-color: #ffff99;
	color: #b1ab00;
	border-color: #b1ab00;
}

#admin:hover {
	background-color: #b1ab00;
	color: white;
	border-color: #b1ab00;
}

</style>
</head>
<body>
<div id="wrapper">
<?php include 'common/header.php';?>




<a><div id="rankings">View rankings</div></a>
<br>
<a href="selectLeague.php"><div id="admin">Admin</div></a>
</div>
</body>
</html>