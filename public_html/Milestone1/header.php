<head>
    <title>Preksha's Simple Bank</title>
    <h1>Simple Bank</h1>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
header('Location: https://it202summer.herokuapp.com/public_html/Milestone1/login.php');
require("config.php");
session_start();
?>
<nav>
    <ul>
        <li>
            <a href="home.php">Home</a>
        </li>
        <li>
            <a href="login.php">Login</a>
        </li>
        <li>
            <a href="register.php">Register</a>
        </li>
        <li>
            <a href="logout.php">Logout</a>
        </li>
    </ul>
</nav>
