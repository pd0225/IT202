<?php
include("header.php");
?>
    <h2>Delete Account</h2>
    <form method="POST">
        <label for="acc_num">Account Number
            <input type="number" id="acc_num" name="acc_num" />
        </label>
        <input type="submit" name="Bank" value="Delete Account"/>
    </form>
<?php
require("common.inc.php");
require("config.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$accnum1 = $_POST["acc_num"];
//example usage, change/move as needed
$stmt = $db->prepare("UPDATE Accounts SET Status = 'Inactive' WHERE acc_num=:acc");
$stmt->execute(array(
    ":acc" => $accnum1
));
var_dump($stmt);
//$result = $stmt->execute();
?>
<?php include 'footer.php';?>
