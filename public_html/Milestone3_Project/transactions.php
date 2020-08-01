<?php
include ("header.php");
?>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require ("config.php");
$conn_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($conn_string, $dbuser, $dbpass);

    $balance =$balance * -1;
    $stmt = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,acctype,amount,exp_total) VALUES (:acc_num,:accnum1, :acctype,:balance,:exp_balance)");
    $result = $stmt->execute(array(
        ":acc_num" => $transfer,
        ":accnum1" => $acc_num ,
        ":acctype" => $acctype,
        ":balance" => $balance,
        ":exp_balance" => $amount
    ));
    $e = $stmt->errorInfo();
    if($e[0] != "00000"){
        var_dump($e);
        echo "setting eee ".$e."<br>";
    }
    $balance =$balance * -1;

    $stmt2 = $db->prepare("INSERT INTO Transactions (acc_src-id, acc_dest_id,acctype,amount,exp_total) VALUES (:acc1,:acc,:acctype,:balance,:exp_balance)");
    $result1 = $stmt2->execute(array(
        ":acc1" => $acc_num,
        ":acc" => $transfer,
        ":acctype" => $acctype,
        ":balance" => $balance,
        ":exp_balance" => $balance
    ));
?>

<h2>Transactions</h2>

    <ul>
        <li>
            <a href="deposit.php">Make a Deposit</a>
        </li>
        <li>
            <a href="withdraw.php">Withdraw Money</a>
        </li>
        <li>
            <a href="transfer.php">Transfer Money</a>
        </li>
        <li>
            <a href="loan.php">Create Loan</a>
        </li>
    </ul>


<?php include 'footer.php';?>