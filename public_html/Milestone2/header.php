<head>
    <title>Preksha's Simple Bank</title>
    <h1>Simple Bank</h1>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
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
        <li>
            <a href="create.php">Create Bank Account</a>
        </li>
        <li>
            <a href="delete.php">Delete Bank Account</a>
        </li>
        <li>
            <a href="edit.php">Update Bank Account</a>
        </li>
        <li>
            <a href="search.php">Search</a>
        </li>
    </ul>
</nav>
