<?php
include("header.php");
?>
    <h2>Loan</h2>
<?php
$email=$_SESSION["user"]["email"];
$accounts=$_SESSION["user"]["accounts"];
$new_arr = array_column($accounts,'acc_num');
$account=$_GET["acc_num"];
?>
    <form method="POST">
        <label for="name">Account
        </label>
        <input type="text" id="name" name="name" value="<?php echo $account; ?>">
        <label for="balance"> Account Balance
            <input type="number" id="balance" name="Balance" />
        </label>
        <label for="loan">Loan Amount
        <input type="number" id="loan" name="Loan" />
        </label>
        <input type="submit" name="loan" value="Create Loan"/>
    </form>
<?php
require("common.inc.php");
include 'footer.php'; ?>
