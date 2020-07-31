<?php
include("header.php");
?>
    <h2>Home</h2>
<?php echo "Welcome to Preksha's Simple Bank " . $_SESSION["user"]["email"];?>
<li>
    <a href="login.php">Login</a>
</li>
<li>
    <a href="register.php">Register</a>
</li>
<?php include 'footer.php';?>
