<?php
include("header.php");
?>
    <h2>Make a Transaction</h2>
<?php
ini_set('display_errors',1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function do_bank_action($account2, $account3, $amountChange, $acctype){
    require("config.php");
    $conn_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    $db = new PDO($conn_string, $dbuser, $dbpass);
    $a2total = 0;//TODO get total of account 2
    $a3total = 0;//TODO get total of account 3
    $query = "INSERT INTO `Transactions` (`acc_src_id`, `acc_dest_id`, `amount`, `acctype`, `exp_total`) 
	VALUES(:p1a2, :p1a3, :p1change, :acctype, :a2total), 
			(:p2a2, :p2a3, :p2change, :acctype, :a3total)";

    $stmt = $db->prepare($query);
    $stmt->bindValue(":p1a2", $account2);
    $stmt->bindValue(":p1a3", $account3);
    $stmt->bindValue(":p1change", $amountChange);
    $stmt->bindValue(":acctype", $acctype);
    $stmt->bindValue(":a2total", $a1total);
    //flip data for other half of transaction
    $stmt->bindValue(":p2a2", $account3);
    $stmt->bindValue(":p2a3", $account2);
    $stmt->bindValue(":p2change", ($amountChange*-1));
    $stmt->bindValue(":acctype", $acctype);
    $stmt->bindValue(":a3total", $a2total);
    $result = $stmt->execute();
    echo var_export($result, true);
    echo var_dump($account2, true);
    echo var_dump ($account3, true);
    echo var_export($stmt->errorInfo(), true);
    return $result;
}
?>
    <form method="POST">
        <input type="text" name="account2" placeholder="Account ID">
        <!-- If our sample is a transfer show other account field-->
        <?php if($_GET['acctype'] == 'transfer') : ?>
            <input type="text" name="account3" placeholder="Other Account ID">
        <?php endif; ?>

        <input type="number" name="amount" placeholder="$0.00"/>
        <input type="hidden" name="type" value="<?php echo $_GET['acctype'];?>"/>
        <label for="transfer">Transfer from

            <select name="Transfer" id="Transfer">

        <!--Based on sample acctype change the submit button display-->
        <input type="submit" value="Transfer Money"/>
    </form>

<?php
if(empty($transfer))
    $transfer= '000000000000';
else $acctype = "Transfer";
if(isset($_POST['acctype']) && isset($_POST['account1']) && isset($_POST['amount'])){
    $acctype = $_POST['acctype'];
    $amount = (int)$_POST['amount'];
    $transfer = $_POST["Transfer"];
    switch($acctype){
        case 'deposit':
            do_bank_action("000000000000", $_POST['account1'], ($amount * -1), $acctype);
            break;
        case 'withdraw':
            do_bank_action($_POST['account2'], "000000000000", ($amount * -1), $acctype);
            break;
        case 'transfer':
            //TODO figure it out
            break;
    }
}
?>