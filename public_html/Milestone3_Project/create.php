<?php
include("header.php");
$email=$_SESSION["user"]["email"];
$accounts=$_SESSION["user"]["accounts"];
$new_arr = array_column($accounts,'acc_num');
?>
<form method="POST">
    <label for="name">Account Name
        <input type="text" id="Name" name="Name" />
    </label>
    <label for="acctype">Account Type
        <select id="AccTyp" name="Account_Type" >
            <option value="Checking">Checking</option>
            <option value="Savings">Savings</option>
            <option value="Loan">Loan</option>
        </select>
    </label>
    <label for="balance">Balance
        <input type="number" id="balance" name="Balance" />
    </label>

            <?php
            foreach($new_arr as $item){
                ?>
                <option value="<?php echo strtolower($item); ?>"><?php echo $item; ?></option>
                <?php
            }
            ?>
    <input type="submit" name="Bank" value="Create Account"/>
</form>
<a style="text-align: center" href=loan.php>Create Loan</a>
<?php
require("common.inc.php");
if(isset($_POST["Created"])){
    $name = $_POST["Name"];

    $acctype = $_POST["acctype"];
    $balance = $_POST["balance"];
    require("config.php");
    $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
    $db = new PDO($connection_string, $dbuser, $dbpass);
    $amount=0.0;
    if(empty($transfer))
        $transfer= '000000000000';
    else {
        $type = "Transfer";
        $stmt1 = $db->prepare("SELECT * FROM Accounts where acc_num=:acc");
        $stmt1->execute(array(
            ":acc" => $transfer
        ));
        $result = $stmt1->fetchAll();
        $amount=$result[0]["balance"];


    }
    $amount=$amount-$balance;
    if(!empty($name) && !empty($acctype)&& !empty($balance) && $balance>=5){

        try{

            try{
                $stmt1 = $db->prepare("SELECT id FROM User where email = :email LIMIT 1");
                $stmt1->execute(array(
                    ":email" => $email
                ));
                $res = $stmt1->fetch(PDO::FETCH_ASSOC);
                $user_id=$res["id"];
            }catch (Exception $e1){
                echo $e1->getMessage();
            }
            if($acctype == 'Savings')
                $APY=3.25;
            else $APY=0.00;
            $stmt = $db->prepare("INSERT INTO Accounts (Name, acctype, User_id, APY) VALUES (:name, :acctype,:user,:APY)");
            $result = $stmt->execute(array(
                ":name" => $name,
                ":acctype"=> $acctype,
                ":user"=>$user_id,
                ":APY"=> $APY
            ));

            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            $stmt1 = $db->prepare("SELECT LPAD(max(id),12,'0') as id FROM Accounts where Name = :name and acctype=:acctype and User_id=:user");
            $stmt1->execute(array(
                ":name" => $name,
                ":acctype"=> $acctype,
                ":user"=>$user_id
            ));
            $res = $stmt1->fetch(PDO::FETCH_ASSOC);
            $acc_id=$res["id"];
            $acc_num=str_pad($acc_id, 12, "0", STR_PAD_LEFT);
            //var_dump($acc_id);
            //var_dump($acc_num);
            $stmt = $db->prepare("update Accounts set acc_num=:acc_num where id=:idnum");
            $result = $stmt->execute(array(
                ":acc_num" => $acc_num,
                ":idnum"=>$acc_id
            ));
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
            //echo $balance;

            $stmt2 = $db->prepare("INSERT INTO Transactions (acc_src-id, acc_dest_id,acctype,amount,exp_total) VALUES (:acc1,:acc,:acctype,:balance,:exp_balance)");
            $result1 = $stmt2->execute(array(
                ":acc1" => $acc_num,
                ":acc" => $transfer,
                ":acctype" => $acctype,
                ":balance" => $balance,
                ":exp_balance" => $balance
            ));
            $e = $stmt2->errorInfo();
            if($e[0] != "00000"){

            }
            $stmt = $db->prepare("update Accounts set Balance= (SELECT sum(Amount) FROM Transactions WHERE acc_src_id=:acc_num) where acc_num=:acc_num");
            $result = $stmt->execute(array(
                ":acc_num" => $acc_num
            ));
            if ($result){
                echo "Successfully created new account for: " . $name;
                $query=$db->prepare("SELECT b.acc_num FROM Accounts b, User a where a.id=b.User_id and a.email=:email");
                $query->execute(array(
                    ":email" => $email
                ));
                $res = $query->fetchAll();
                $_SESSION["user"]["accounts"]=$res;
                echo var_export($_SESSION, true);
                header("Location: home.php");
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

        echo "<div> Account name, account type and balance must not be empty. Balance must be at least 5 Dollars.<div>";
    }
}
$stmt = $db->prepare("SELECT * FROM Accounts");
$stmt->execute();
?>
<?php include 'footer.php';?>