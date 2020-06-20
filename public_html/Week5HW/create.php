<form method="POST">
    <label for="name">Account Name
        <input type="text" id="Name" name="Name" />
    </label>
    <label for="AccountNum">Account Number
        <input type="number" id="AccountNum" name="Account_Number" />
    </label>
    <label for="AccountType">Account Type
        <input type="text" id="AccountType" name="Account_Type" />
    </label>
    <label for="AccountBalance">Balance
        <input type="number" id="AccountBalance" name="Account_Balance" />
    </label>
    <input type="submit" name="Account" value="Create Account"/>
</form>
<?php
require("common.inc.php");
$db = getDB();


if(isset($_POST["Account"])){
    $name = $_POST["Name"];
    $AccountNum = $_POST["Account_Number"];
    $AccountType = $_POST["Account_Type"];
    $AccountBalance = $_POST["Account_Balance"];
    if(!empty($name) && !empty($AccountNum)&& !empty($AccountType)&& !empty($AccountBalance)){
        require("config.php");
        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        try{
            $db = new PDO($connection_string, $dbuser, $dbpass);
            $stmt = $db->prepare("INSERT INTO Account (Name, Account_Number, Account_Type,Account_Balance) VALUES (:name, :AccountNum, :AccountType,:AccountBalance)");
            $result = $stmt->execute(array(
                ":name" => $name,
                ":AccountNum" => $AccountNum,
                ":AccountType"=> $AccountType,
                ":AccountBalance"=> $AccountBalance
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                //echo var_export($result, true);
                if ($result){
                    echo "Successfully inserted new account: " . $name;
                }
                else{
                    echo "Error inserting record";
                }
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "<div>Name, Account Number, Account Type and Account Balance must not be empty.<div>";
    }
}
$stmt = $db->prepare("SELECT * FROM Account");
$stmt->execute();
?>