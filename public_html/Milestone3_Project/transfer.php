<?php
include("header.php");
?>
<h2>Make a Transfer</h2>
<?php
$email=$_SESSION["user"]["email"];
$accounts=$_SESSION["user"]["accounts"];
$new_arr = array_column($accounts,'acc_num');
$account=$_GET["acc_num"];
?>
    <form method="POST">
        <label for="name">From
            <input type="text" id="name" name="name"/>
        </label>
        </label>
        <label for="name">To
            <input type="text" id="name" name="name"/>
        </label>
            <?php
            foreach($new_arr as $item){
                ?>
                <option value="<?php echo strtolower($item); ?>"><?php echo $item; ?></option>
                <?php
            }
            ?>
        </select>
        <label for="balance">Amount
            <input type="number" id="balance" name="Balance" />
        </label>
        <input type="submit" name="Transfer" value="Transfer"/>
    </form>
<?php
require("common.inc.php");
require("config.php");
if(isset($_POST["Transfer"])){

    $name = $_POST["Name"];
    $name1 = $_POST["Name1"];
    $balance = $_POST["Balance"];

    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    $db = new PDO($connection_string, $dbuser, $dbpass);
    $stmt1 = $db->prepare("SELECT * FROM Accounts where acc_num=:acc");
    $stmt1->execute(array(
        ":acc" => $name
    ));
    $result = $stmt1->fetchAll();
    $amount=$result[0]["Balance"];
    $amount=$amount-$balance;
    $stmt1->execute(array(
        ":acc" => $name1
    ));
    $result = $stmt1->fetchAll();
    $amount1=$result[0]["Balance"];
    $amount1=$amount1+$balance;

    if(!empty($name) && !empty($balance) && $balance>0 &&  $amount>5){
        // echo "before major if 3a<br>";

        //  echo "before major if 3b<br>";

        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        try{
            $db = new PDO($connection_string, $dbuser, $dbpass);
            $balance=$balance * -1;
            $stmt = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,acctype,amount,exp_total) VALUES (:acc_num,:accnum1, :acctype,:balance,:exp_balance)");
            $result = $stmt->execute(array(
                ":acc_num" => $name,
                ":accnum1" => $name1,
                ":acctype" => "Transfer",
                ":balance" => $balance,
                ":exp_balance" => $amount
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                echo "setting eee ".$e."<br>";
                //echo var_export($e, true);
            }
            $balance =$balance * -1;
            //echo $balance;

            $stmt2 = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,acctype,amount,exp_total) VALUES (:acc_num,:accnum1, :acctype,:balance,:exp_balance)");
            $result1 = $stmt2->execute(array(
                ":acc1" => $name1,
                ":acc" => $name,
                ":acctype" => "Transfer",
                ":balance" => $balance,
                ":exp_balance" => $amount1
            ));
            $e = $stmt2->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                $stmt2->debugDumpParams();
                echo "setting AAAAAeee ".$e."<br>";
                //echo var_export($e, true);
            }
            $stmt = $db->prepare("update Accounts set Balance= (SELECT sum(Amount) FROM Transactions WHERE acc_src_id=:accnum) where acc_num=:acc_num");
            $result = $stmt->execute(array(
                ":acc_num" => $name
            ));
            $res = $stmt->execute(array(
                ":acc_num" => $name1
            ));
            if ($result){
                echo "Successfully transferred ".$balance." from account " . $name." to account ".$name1;
                header("Location: home.php");
            }
            else{
                echo "Error inserting record";
            }
        }
        catch (Exception $e){
            echo "Error inserting record 1";
            echo $e->getMessage();
        }
    }

    else{
        echo "Account name and amount must not be empty. Amount should be greater than zero. Account should have a five-dollar remaining balance.";
    }
}
$stmt = $db->prepare("SELECT * FROM Accounts");
$stmt->execute();
?>
<?php include 'footer.php';?>
