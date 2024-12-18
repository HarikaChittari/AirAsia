<?php

// SESSION MANAGEMENT - Check for the cookie and determine role
// Add this to each webpage to ensure a session is checked for and enforced!

// require_once 'checksession.php';

// ACCESS RESTRICTION

//	if ($_SESSION['role'] != 1) {
//		header("Location: unauthorized.php"); // Redirect to unauthorized page
//		exit();
//	}

// USER ROLES FOR THE SESSION

// $_SESSION['role'] = 1; ADMIN
// $_SESSION['role'] = 0; STANDARD USER

session_start();

if(!isset($_SESSION['username'])){
	header("Location: login.php");
	exit();
}

?>