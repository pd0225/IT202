<?php
include("header.php");

$email=$_SESSION["user"]["email"];
$accounts=$_SESSION["user"]["accounts"];
$new_arr = array_column($accounts,'acc_num');
$account=$_GET["acc_num"];
?>
<h2>Withdraw</h2>

    <form method="POST">
        <label for="name">Account
            <input type="text" id="Name" name="Name" value="<?php echo $account; ?>">
        </label>

        <label for="balance">Amount
            <input type="number" id="balance" name="Balance" />
        </label>
        <input type="submit" name="Withdraw" value="Withdraw"/>
    </form>
<?php
require("common.inc.php");
if(isset($_POST["Withdraw"])){
    $name = $_POST["Name"];
    $balance = $_POST["Balance"];
    $balance=$balance * -1;
    require("config.php");
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    $db = new PDO($connection_string, $dbuser, $dbpass);
    $stmt1 = $db->prepare("SELECT * FROM Accounts where acc_num=:acc");
    $stmt1->execute(array(
        ":acc" => $name
    ));
    $result = $stmt1->fetchAll();
    $amount=$result[0]["Balance"];
    $amount=$amount+$balance;
    if(!empty($name) && !empty($balance) && $amount>=5){

        try{

            $stmt = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,acctype,amount,exp_total) VALUES (:acc_num,:accnum1, :acctype,:balance,:exp_balance)");
            $result = $stmt->execute(array(
                ":acc_num" => $name,
                ":accnum1" => "000000000000",
                ":acctype" => "Withdraw",
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                echo "setting eee ".$e."<br>";
            }
            $balance =$balance * -1;
            echo $balance;
            $stmt2 = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,acctype,amount,exp_total) VALUES (:acc1,:acc, :acctype,:balance,:exp_balance)");
            $result1 = $stmt2->execute(array(
                ":acc1" => "000000000000",
                ":acc" => $name,
                ":acctype" => "Withdraw",
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt2->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                $stmt2->debugDumpParams();
            }
            $stmt = $db->prepare("update Accounts set Balance= (SELECT sum(Amount) FROM Transactions WHERE acc_src_id=:acc_num) where acc_num=:acc_num");
            $result = $stmt->execute(array(
                ":acc_num" => $name
            ));
            if ($result){
                echo "Successfully inserted: " . $name;
                header("Location: home.php");

            }
            else{
                echo "Error inserting record.";
            }
        }
        catch (Exception $e){
            echo "Error inserting record 1.";
            echo $e->getMessage();
        }
    }
    else{
        echo "<div>Account name and amount must not be empty. Withdraw amount should not be more than your total balance. A five-dollar balance must be remain.<div>";
    }
}
$stmt = $db->prepare("SELECT * FROM Accounts");
$stmt->execute();
?>
<?php include 'footer.php';?>
