<?php include("header.php");?>
<h2>Create Account</h2>

<script src="js/script.js"></script>
<form method="POST">
    <label for="name">Account Name
        <input type="text" id="Name" name="Name" />
    </label>
    <label for="acctype">Account Type
        <select id="acctype" name="acctype" >
            <option value="Checking">Checking</option>
            <option value="Savings">Savings</option>
            <option value="Loan">Loan</option>
        </select>
    </label>
    <label for="balance">Balance
        <input type="number" id="balance" name="Balance" />
    </label>
    <input type="submit" name="created" value="Create Account"/>
</form>
<?php
if(!isset($_SESSION["user"])){
    die(header("Location: login.php"));
}
if(isset($_POST["created"])) {
    $name = "";
    $balance = -1;
    if(isset($_POST["name"]) && !empty($_POST["name"])){
        $name = $_POST["name"];
    }
    if(isset($_POST["balance"]) && !empty($_POST["balance"])){
        if(is_numeric($_POST["balance"])){
            $balance = (int)$_POST["balance"];
        }
    }
    if(empty($name) || $balance < 0 ){
        echo "Account name and balance must not be empty. Balance should be at least 0.";
        die();
    }
    try {
        $query = "SELECT MAX(id) as max from Accounts";
        echo "<br>$query<br>";
        $stmt = getDB()->prepare($query);
        $stmt->execute();
        echo var_export($stmt->errorInfo(), true);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        $max = (int)$r["max"];
        $max += 1;
        $acc_num = str_pad($max,12,"0",STR_PAD_LEFT);
        echo $max;
        echo $acc_num;
        $query = "INSERT INTO Accounts(account_number, user_id, name) VALUES(:an, :id, :name)";
        echo "<br>$query<br>";
        $stmt = getDB()->prepare($query);
        $stmt->execute(array(":an"=>$acc_num, ":id"=>$_SESSION["user"]["id"], ":name"=>$name));
        echo var_export($stmt->errorInfo(), true);
        $worldAcct = 000000000000;
        $query = "Select id from Accounts where acc_num = '000000000000'"; //TODO fetch world account from DB so we can get the ID, I defaulted to -1 so you implement this portion. Do not hard code the value here.
        echo "<br>$query<br>";
        $stmt = getDB()->prepare($query);
        $stmt->execute();
        echo var_export($stmt->errorInfo(), true);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $worldAcct = $result["id"];
        $query = "INSERT INTO Transactions(acc_src_id, acc_dest_id,`amount`, `acctype`) VALUES (:src, :dest, :change, :acctype)";
        echo "<br>$query<br>";


        if(isset($query) && !empty($query)) {
            $stmt = getDB()->prepare($query);
            $balance *= -1;
            $result = $stmt->execute
            (array(
                    ":src" => $worldAcct,
                    ":dest" => $max,
                    ":change"=>$balance,
                    ":acctype"=>"new"
                )
            );
            echo var_export($stmt->errorInfo(), true);
            echo "<br>$query<br>";
            //part 2
            $balance *= -1;//flip
            $result = $stmt->execute(array(
                ":src" => $max,
                ":dest" => $worldAcct,
                ":change"=>$balance,
                ":acctype"=>"new" //or it can be "create" or "new" if you want to distinguish between deposit and opening an account
            ));
            echo var_export($stmt->errorInfo(), true);
            $query = "SELECT SUM(`amount`) as balance from Transactions where acc_src_id = :id";
            echo "<br>$query<br>";
            $stmt = getDB()->prepare($query);
            $stmt->execute([":id"=>$max]);
            echo var_export($stmt->errorInfo(), true);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
//TODO, should properly check to see if we have data and all
            $sum = (int)$result["balance"];
            $query = "UPDATE Accounts set balance = :bal where id = :id";
            echo "<br>$query<br>";
            $stmt = getDB()->prepare($query);
            $stmt->execute([":bal"=>$sum, ":id"=>$max]);
            echo var_export($stmt->errorInfo(), true);

        }
        else{
            echo "Failed to find insert_table_accounts.sql file";
        }
    }
    catch (Exception $e){
        echo $e->getMessage();

    }
}
?>
<?php include 'footer.php';?>
