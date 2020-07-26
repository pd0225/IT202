<?php
include_once(__DIR__."/header.php");
if(Common::is_logged_in()){
    //this will auto redirect if user isn't logged in
}
?>
<div class="container-fluid">
    <h4>Home</h4>
    <p>Welcome to Preksha's Simple Bank, <?php echo Common::get_username();?></p>

