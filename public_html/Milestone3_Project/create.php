<?php
include("header.php");
?>
    <h2>Create Bank Account</h2>
<script src="js/script.js"></script>
<!-- note although <script> tag "can" be self terminating some browsers require the
full closing tag-->
<form method="POST">
    <label for="account">Account Name
        <input type="text" id="account" name="name" />
    </label>
    <label for="acctype">Account Type
        <input type="text" id="acctype" name="acctype" />
    </label>
    <label for="b">Balance
        <input type="number" id="b" name="balance" />
    </label>
    <input type="submit" name="created" value="Create Account"/>
</form>

<?php
require ("common.inc.php");
if(isset($_POST["created"])){
    $name = $_POST["name"];
    $acctype = $_POST["acctype"];
    $balance = $_POST["balance"];
    if(!empty($name) && !empty($acctype)&& !empty($balance)){
        require("config.php");
        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        try{
            $db = new PDO($connection_string, $dbuser, $dbpass);
            try{
                $stmt1 = $db->prepare("SELECT id FROM Users where email = :email LIMIT 1");
                $stmt1->execute(array(
                    ":email" => $email
                ));
                $res = $stmt1->fetch(PDO::FETCH_ASSOC);
                $user_id=$res["id"];
            }catch (Exception $e1){
                echo $e1->getMessage();
            }
            $stmt = $db->prepare("INSERT INTO Accounts (Name, type, User_id) VALUES (:name, :acctype,:user)");
            $result = $stmt->execute(array(
                ":name" => $name,
                ":acctype"=> $acctype,
                ":user"=>$user_id
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            $stmt1 = $db->prepare("SELECT max(id) as id FROM Accounts where Name = :name and type=:acctype and User_id=:user");
            $stmt1->execute(array(
                ":name" => $name,
                ":acctype"=> $acctype,
                ":user"=>$user_id
            ));
            $res = $stmt1->fetch(PDO::FETCH_ASSOC);
            $acc_id=$res["id"];
            $acc_num=str_pad($acc_id, 12, "0", STR_PAD_LEFT);
            //echo $acc_id;
            //echo " ".$account_num."<br>";
            $stmt = $db->prepare("update Accounts set acc_num=:acc_num where id=:idnum");
            $result = $stmt->execute(array(
                ":acc_num" => $acc_num,
                ":idnum"=>$acc_id
            ));
            $stmt = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dst_id,acctype,amount,exp_total) VALUES (:acc_num,:accnum1, :acctype,:balance,:exp_balance)");
            $result = $stmt->execute(array(
                ":acc_num" => $acc_num,
                ":accnum1" => "000000000000",
                ":acctype" => "Deposit",
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                var_dump($e);
                echo "setting eee ".$e."<br>";
            }
            $balance =$balance * -1;
            //echo $balance;

            $stmt2 = $db->prepare("INSERT INTO Transactions (acc_src_id, acc_dst_id,acctype,amount,exp_total) VALUES (:acc1,:acc, :acctype,:balance,:exp_balance)");
            $result1 = $stmt2->execute(array(
                ":acc1" => "000000000000",
                ":acc" => $account_num,
                ":acctype" => "WithDraw",
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt2->errorInfo();
            if($e[0] != "00000"){
                //var_dump($e);
                //$stmt2->debugDumpParams();
                //echo "setting AAAAAeee ".$e."<br>";
            }
            $stmt = $db->prepare("update Accounts set Balance= (SELECT sum(Amount) FROM Transactions WHERE acc_src_id=:acc_num) where acc_num=:acc_num");
            $result = $stmt->execute(array(
                ":acc_num" => $acc_num
            ));
            if ($result){
                echo "Successfully Created new Account for : " . $name;
            }
            else{
                echo "Error inserting record";
            }
        }
        catch (Exception $e){

            echo $e->getMessage();
        }
    }

    else{

        echo "<div>Account Name, Account Type and Account Balance must not be empty.<div>";
    }
}
$stmt = $db->prepare("SELECT * FROM Accounts");
$stmt->execute();
?>