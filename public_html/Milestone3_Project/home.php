
<?php
include("header.php");
require("common.inc.php");
require("config.php");
$accounts=$_SESSION["user"] ["accounts"];
$new_arr = array_column($accounts, "acc_num");
    foreach ($new_arr as $item) {
        echo "<a href=acc_prof.php?account=". $item.">".$item."</a>";
        echo "<a href=deposit.php?acc_num=". $item.">Deposit</a>";
        echo "<a href=withdraw.php?acc_num=".$item.">Withdraw</a>";
        echo "<a href=transfer.php?acc_num=".$item.">Transfer</a>";
        echo '<br>';
    }
?>

<h2>Home</h2>
<?php echo "Welcome to Preksha's bank, " . $_SESSION["user"]["email"]
?>

