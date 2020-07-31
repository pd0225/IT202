<head>
    <title>Simple Bank</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <img id="psb" src="psb.jpg" alt="Simple Bank Logo" height="140" width="160" align="left" style="margin-left: 39pc;">
    <h1>Preksha's Simple Bank</h1>
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
            <a href="logout.php">Logout</a>
        </li>
    </ul>
    <ul>
        <li>
        <a href="bankacc.php">Banking Options</a>
        </li>
    </ul>

</nav>
