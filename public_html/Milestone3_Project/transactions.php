<?php
include ("header.php");
?>
    <form method="POST">
        <input type="text" name="accountId" placeholder="Account ID">
        <input type="text" name="name" placeholder="Account Name">
        <input type="hidden" name="acctype" value="<?php echo $_GET['acctype'];?>"/>

        <!--Based on sample type change the submit button display-->
        <input type="submit" value="View Account Activity"/>
    </form>
<?php
if (Common::is_logged_in()) {
    echo "<h4>User: " . $name . "</h4>";
    echo "<h4>Account Type: " . $acctype . "</h4>";
    echo "<h4>Account Balance: " . $amount . "</h4>";
}
?>