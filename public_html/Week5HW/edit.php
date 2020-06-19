<?php
require("config.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$AccountNum = -1;
$result = array();
function get($arr, $key){
    if(isset($arr[$key])){
        return $arr[$key];
    }
    return "";
}

?>

    <form method="POST">
        <label for="name">Account Name
            <input type="text" id="Name" name="Name" value="<?php echo get($result, "Name");?>" />
        </label>
        <label for="AccountNum">Account Number
            <input type="number" id="AccountNum" name="Account_Number" value="<?php echo get($result, "Account_Number");?>" />
        </label>
        <label for="acctype">Account Type
            <input type="text" id="AccountType" name="Account_Type" value="<?php echo get($result, "Account_Type");?>" />
        </label>
        <label for="balance">Balance
            <input type="number" id="AccountBalance" name="Account Balance" value="<?php echo get($result, "Account_Balance");?>" />
        </label>
        <input type="submit" name="updated" value="Update Thing"/>
    </form>

<?php
if(isset($_POST["updated"]) || isset($_POST["created"])){
    $name = $_POST["Name"];
    $AccountNum1 = $_POST["Account_Number"];
    $AccountType = $_POST["Account_Type"];
    $AccountBalance = $_POST["Account_Balance"];
    if(!empty($name) && !empty($AccountNum1)&& !empty($AccountType)&& !empty($AccountBalance)){
        try{
            $stmt = $db->prepare("UPDATE Account set Name='$name', Account_Type='$AccountType', Account_Balance=$AccountBalance where Account_Number=$AccountNum1");
            $result = $stmt->execute();
            var_dump($stmt);
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                echo var_export($result, true);
                if ($result){
                    echo "Successfully inserted or updated thing: " . $name;
                }
                else{
                    echo "Error inserting or updating record";
                }
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Name and quantity must not be empty.";
    }
}
?>