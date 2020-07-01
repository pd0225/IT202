<head>
    <title>Preksha's Simple Bank</title>
    <h1>Simple Bank</h1>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
session_start();
?>
<div class="navbar">
    <a href="home.php">Home</a>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
    <a href="logout.php">Logout</a>
    <div class="dropdown">
        <button class="dropbtn">Bank Account
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a href="create.php">Create Bank Account</a>

            <a href="delete.php">Delete Bank Account</a>

            <a href="edit.php">Update Bank Account</a>

            <a href="search.php">Search</a>
        </div>
    </div>
</div>


    </ul>
</nav>
