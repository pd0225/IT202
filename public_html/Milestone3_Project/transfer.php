<?php
include("header.php");

$email=$_SESSION["user"]["email"];
$accounts=$_SESSION["user"]["accounts"];
$new_arr = array_column($accounts,'acc_num');
//var_dump($new_arr);
echo "Hello". $email;?>
    <form method="POST">
        <label for="name">From
        </label>
        <select name="Name" id="Name">
            <?php

            foreach($new_arr as $item){
                ?>
                <option value="<?php echo strtolower($item); ?>"><?php echo $item; ?></option>
                <?php
            }
            ?>
        </select>
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
//echo "before major if 1";
require("common.inc.php");

//echo "before major if 2";
if(isset($_POST["Transfer"])){
//echo "before major if 2a";
    $name = $_POST["Name"];
    $name1 = $_POST["Name1"];

    $balance = $_POST["Account Balance"];
    //$balance=$balance * -1;
    //echo "before major if 3".$name;
    //echo "before major if 3".$name1;
    if(!empty($name) && !empty($balance)){
        // echo "before major if 3a<br>";
        require("config.php");
        //  echo "before major if 3b<br>";

        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        //  echo "Inside major if";
        try{
            //echo "before major if 3c<br>";
            $db = new PDO($connection_string, $dbuser, $dbpass);
            //echo "before major if 3d<br>";

            $stmt = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,acctype,amount,exp_total) VALUES (:acc_num,:accnum1, :acctype,:balance,:exp_balance)");
            $result = $stmt->execute(array(
                ":acc_num" => $name1,
                ":accnum1" => $name,
                ":acctype" => "Deposit",
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                echo "setting eee ".$e."<br>";
                //echo var_export($e, true);
            }
            $balance =$balance * -1;
            //echo $balance;

            $stmt2 = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dest_id,acctype,amount,expected_total) VALUES (:acc1,:acc, :acctype,:balance,:exp_balance)");
            $result1 = $stmt2->execute(array(
                ":acc1" => $name,
                ":acc" => $name1,
                ":acctype" => "Withdraw",
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt2->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                $stmt2->debugDumpParams();
                echo "setting AAAAAeee ".$e."<br>";
                //echo var_export($e, true);
            }
            $stmt = $db->prepare("update Accounts set AccountBalance= (SELECT sum(Amount) FROM Transactions WHERE acc_src_id=:acc_num) where acc_num=:acc_num");
            $result = $stmt->execute(array(
                ":acc_num" => $name
            ));
            $res = $stmt->execute(array(
                ":acc_num" => $name1
            ));

            if ($result){
                //if($res){
                echo "Successfully transferred ".$balance." from account " . $name." to account ".$name1;
            }
            else{
                echo "Error inserting record";
            }
        }
            //}
        catch (Exception $e){
            echo "Error inserting record 1";
            echo $e->getMessage();
        }
    }

    else{
        // echo "did not go through if";
        echo "<div>Account name and amount must not be empty.<div>";
    }
}
$stmt = $db->prepare("SELECT * FROM Accounts");
$stmt->execute();
?>