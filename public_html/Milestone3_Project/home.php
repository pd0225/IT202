
<?php
include_once(__DIR__."/header.php");
if(Common::is_logged_in()){
    //this will auto redirect if user isn't logged in
}
?>
<h2>Home</h2>
<?php echo "Welcome to Preksha's bank, " . $_SESSION["user"]["email"]
?>

