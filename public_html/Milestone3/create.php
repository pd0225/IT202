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
    <label for="b">Balance
        <input type="number" id="b" name="balance" />
    </label>
    <input type="submit" name="created" value="Create Account"/>
</form>

<?php
if(isset($_POST["created"])){
    $name = $_POST["name"];
    $balance = $_POST["balance"];
    if(isset($_POST["name"]) && !empty($_POST["name"])){
        $name = $_POST["name"];
    }
    if(isset($_POST["balance"]) && !empty($_POST["balance"])){
        if(is_numeric($_POST["balance"])){
            $balance = (int)$_POST["balance"];
        }
    }
    //If name or balance is invalid, don't do the DB part
    if(empty($name) || $balance < 0 ){
        echo "Account name must not be empty and account balance must be greater than or equal to 0";
        die();//terminates the rest of the script
    }
    try {
        require("common.inc.php");
//find the max id in the table
        $query = "SELECT MAX(id) as max from Accounts";
        echo "<br>$query<br>";
        $stmt = getDB()->prepare($query);
        $stmt->execute();
        echo var_export($stmt->errorInfo(), true);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        $max = (int)$r["max"];
        $max += 1;
        $acc_num = str_pad($str,12,"0",STR_PAD_LEFT);
//insert the new account number and associate it with the logged in user
        $query = "INSERT INTO Accounts(acc_num, user_id, name) VALUES(:an, :id, :name)";
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
//end fetch world account id

        $query = "INSERT INTO Transactions(acc_src_id, acc_dest_id,`amount`, `acc_type`) VALUES (:src, :dest, :change, :acc_type)";
        echo "<br>$query<br>";


        if(isset($query) && !empty($query)) {
            $stmt = getDB()->prepare($query);
//part 1
            $balance *= -1;//flip
            $result = $stmt->execute
            (array(
                    ":src" => $worldAcct,
                    ":dest" => $max, //<- should really get the last insert ID from the account query, but $max "should" be accurate
                    ":change"=>$balance,
                    ":acc_type"=>"deposit" //or it can be "create" or "new" if you want to distinguish between deposit and opening an account

                )
            );
            echo var_export($stmt->errorInfo(), true);
            echo "<br>$query<br>";
            //part 2
            $balance *= -1;//flip
            $result = $stmt->execute(array(
                ":src" => $max,
                ":dest" => $worldAcct, //<- should really get the last insert ID from the account query, but $max "should" be accurate
                ":change"=>$balance,
                ":acc_type"=>"deposit" //or it can be "create" or "new" if you want to distinguish between deposit and opening an account
            ));
            echo var_export($stmt->errorInfo(), true);
//get new balance
            $query = "SELECT SUM(`amount`) as balance from Transactions where acc_src_id = :id";
            echo "<br>$query<br>";
            $stmt = getDB()->prepare($query);
            $stmt->execute([":id"=>$max]);
            echo var_export($stmt->errorInfo(), true);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
//TODO, should properly check to see if we have data and all
            $sum = (int)$result["balance"];
//update balance
            $query = "UPDATE Accounts set balance = :bal where id = :id";
            echo "<br>$query<br>";
            $stmt = getDB()->prepare($query);
            $stmt->execute([":bal"=>$sum, ":id"=>$max]);
            echo var_export($stmt->errorInfo(), true);

        }
        else{
            echo "Failed to find Insert_table_Accounts.sql file";
        }
    }
    catch (Exception $e){
        echo $e->getMessage();

    }
}
?>