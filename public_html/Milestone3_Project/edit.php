<?php
include("header.php");
?>
<h2>Update Bank Account</h2>
<?php
require("config.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$acc_num = -1;
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
        <input type="text" id="name" name="name" value="<?php echo get($result, "name");?>" />
    </label>
    <label for="acc_num">Account Number
        <input type="number" id="acc_num" name="acc_num" value="<?php echo get($result, "acc_num");?>" />
    </label>
    <label for="acc_type">Account Type
        <input type="text" id="acc_type" name="acc_type" value="<?php echo get($result, "acc_type");?>" />
    </label>
    <label for="b">Balance
        <input type="number" id="b" name="balance" value="<?php echo get($result, "balance");?>" />
    </label>
    <input type="submit" name="updated" value="Update Account"/>
</form>

<?php
if(isset($_POST["updated"])){
    $name = $_POST["name"];
    $acc_num = $_POST["acc_num"];
    $acc_type = $_POST["acc_type"];
    $balance = $_POST["Balance"];
    if(!empty($name) && !empty($acc_num)&& !empty($acc_type)&& !empty($balance)){
        try{
            $stmt = $db->prepare("UPDATE Accounts set Name='$name', Account_type='$acc_type', Balance=$balance where Account_Number=$acc_num");
            $result = $stmt->execute();
            var_dump($stmt);
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                echo var_export($result, true);
                if ($result){
                    echo "Successfully inserted or updated account: " . $name;
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
        echo "Account Name and Account Balance must not be empty.";
    }
}
?>
