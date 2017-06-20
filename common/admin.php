<?php

if ($_SESSION["isLogged"]) {
	require 'common/header.php';
} else {
	die("You do not have permission to view this page.");
}


?>