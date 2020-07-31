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
        <a href="acc_prof.php">Bank Profile</a>
        </li>
        <li>
            <a href="profile.php">Account Details</a>
        </li>
    </ul>
    <ul>
        <a href="#" style="color: darkslategrey; font-weight: bold;">Bank Account: </a>
    </ul>
    <ul>
        <li>
            <a href="transfer.php">Transfer</a>
        </li>
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
        <ul>
            <li>
                <a href="deposit.php">Deposit</a>
            </li>
            <li>
                <a href="withdraw.php">Withdraw</a>
            </li>
        </ul>

    </ul>

</nav>
