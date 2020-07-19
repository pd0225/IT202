<?php
include("header.php");

$email=$_SESSION["user"]["email"];
$accounts=$_SESSION["user"]["accounts"];
$new_arr = array_column($accounts,'acc_num');
$account=$_GET["acc_num"];
//var_dump($new_arr);
echo "Hello". $email;?>
    <form method="POST">
        <label for="name">From
        </label>
        <input type="text" id="Name" name="Name" value="<?php echo $account; ?>" readonly>
        <label for="to">To
        </label>
        <select name="Name1" id="Name1">
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
if(isset($_POST["Transfer"])){

    $name = $_POST["Name"];
    $name1 = $_POST["Name1"];
    $balance = $_POST["Balance"];

    if(!empty($name) && !empty($balance) && $balance>0 ){
        require("config.php");

        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        try{
            $db = new PDO($connection_string, $dbuser, $dbpass);
            $balance=$balance * -1;
            $stmt = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,type,amount,exp_total) VALUES (:acc_num,:accnum1, :acctype,:balance,:exp_balance)");
            $result = $stmt->execute(array(
                ":acc_num" => $name,
                ":accnum1" => $name1,
                ":type" => "Transfer",
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                echo "setting eee ".$e."<br>";
            }
            $balance =$balance * -1;

            $stmt2 = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,type,amount,exp_total) VALUES (:acc1,:acc, :acctype,:balance,:exp_balance)");
            $result1 = $stmt2->execute(array(
                ":acc1" => $name1,
                ":acc" => $name,
                ":type" => "Transfer",
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt2->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                $stmt2->debugDumpParams();
                echo "setting AAAAAeee ".$e."<br>";

            }
            $stmt = $db->prepare("update Accounts set Balance= (SELECT sum(Amount) FROM Transactions WHERE acc_src_id=:acc_num) where acc_num=:acc_num");
            $result = $stmt->execute(array(
                ":acc_num" => $name
            ));
            //$stmt = $db->prepare("update Accounts set Balance= (SELECT sum(Amount) FROM Transactions WHERE acc_dest_id=:acc_num) where acc_num=:acc_num");
            $res = $stmt->execute(array(
                ":acc_num" => $name1
            ));

            if ($result){
                //if($res){
                echo "Successfully transferred ".$balance." from account " . $name." to account ".$name1;
                header("Location: home.php");
            }
            else{
                echo "Error inserting record.";
            }
        }
            //}
        catch (Exception $e){
            echo "Error inserting record 1.";
            echo $e->getMessage();
        }
    }

    else{
        // echo "did not go through if";
        echo "<div>Account name and amount must not be empty. Amount should be greater than zero.<div>";
    }
}
$stmt = $db->prepare("SELECT * FROM Accounts");
$stmt->execute();
?>