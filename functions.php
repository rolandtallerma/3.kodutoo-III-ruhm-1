<?php 
	// functions.php
	require("/home/rolatall/config.php");
	
	// et saab kasutada $_SESSION muutujaid
	// kõigis failides mis on selle failiga seotud
	session_start(); 
	
	/* ÜHENDUS */
	$database = "if16_rolatall_3";
	$mysqli = new mysqli($serverHost, $serverUsername,  $serverPassword, $database);

	
?>