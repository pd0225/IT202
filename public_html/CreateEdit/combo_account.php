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
if(isset($_GET["AccountNum"])){
    $AccountNum = $_GET["AccountNum"];
    $stmt = $db->prepare("SELECT * FROM Account where Account_Number = :AccountNum");
    $stmt->execute([":AccountNum"=>$AccountNum]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$result){
        $AccountNum = -1;
    }
}
else{
    echo "No Account Number provided in url, don't forget this or sample won't work.";
}
?>

    <form method="POST">
        <label for="name">Account Name
            <input type="text" id="Name" name="Name" value="<?php echo get($result, "Name");?>" />
        </label>
        <label for="AccountNum">Account Number
            <input type="number" id="AccountNum" name="Account_Number" value="<?php echo get($result, "Account_Number");?>" />
        </label>
        <label for="AccountType">Account Type
            <input type="text" id="AccountType" name="Account_Type" value="<?php echo get($result, "Account_Type");?>" />
        </label>
        <label for="AccountBalance">Account Balance
            <input type="number" id="AccountBalance" name="Account_Balance" value="<?php echo get($result, "Account_Balance");?>" />
        </label>
        <?php if($AccountNum > 0):?>
            <input type="submit" name="updated" value="Update Account"/>
        <?php elseif ($AccountNum < 0):?>
            <input type="submit" name="created" value="Create Account"/>
        <?php endif;?>
    </form>

<?php
if(isset($_POST["updated"]) || isset($_POST["created"])){
    $name = $_POST["Name"];
    $AccountNum = $_POST["Account_Number"];
    $AccountType = $_POST["Account_Type"];
    $AccountBalance = $_POST["Account Balance"];
    if(!empty($name) && !empty($AccountNum1)&& !empty($AccountType)&& !empty($AccountBalance)){
        try{
            if(isset($_POST["updated"])) {
                $stmt = $db->prepare("UPDATE Account set Name='$name', Account_Type='$AccountType', Account_Balance=$AccountBalance where Account_Number=$AccountNum");
                $result = $stmt->execute();
                var_dump($stmt);
            }
            else{
                $stmt = $db->prepare("INSERT INTO Account (Name, Account_Number, Account_Type,Account_Balance) VALUES (:name, :AccountNum, :AccountType,:AccountBalance)");
                $result = $stmt->execute(array(
                    ":name" => $name,
                    ":AccountNum" => $AccountNum1,
                    ":AccountType"=> $AccountType,
                    ":AccountBalance"=> $AccountBalance
                ));
            }
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
        echo "Name and amount must not be empty.";
    }
}
?>