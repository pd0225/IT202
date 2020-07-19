<head>
    <title>Preksha's Simple Bank</title>
    <img src="Screen Shot 2020-07-19 at 11.34.36 AM" alt="Simple Bank Logo" height="80" align=left>
    <h1>Simple Bank</h1>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
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
    <ul>
        <a href="acc_prof.php">Profile</a>
    </ul>
    <ul>
        <a href="#" style="color: darkslategrey; font-weight: bold;">Bank Account: </a>
    </ul>
    <ul>
        <li>
            <a href="transactions.php">Transactions</a>
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
