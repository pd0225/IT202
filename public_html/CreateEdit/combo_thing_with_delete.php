<?php
require("config.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$AccNum = -1;
$result = array();
function get($arr, $key){
    if(isset($arr[$key])){
        return $arr[$key];
    }
    return "";
}
if(isset($_GET["AccountNum"])){
    $AccountNum = $_GET["AccountNum"];
    $stmt = $db->prepare("SELECT * FROM Bank_Account where Account_Num = :AccountNum");
    $stmt->execute([":AccountNum"=>$AccNum]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$result){
        $AccountNum = -1;
    }
}
else{
    echo "No AccountNum provided in url, don't forget this or sample won't work.";
}
?>

    <form method="POST">
        <label for="name">Account Name
            <input type="text" id="Name" name="Name" value="<?php echo get($result, "Name");?>" />
        </label>
        <label for="AccountNum">Account Number
            <input type="number" id="AccountNum" name="Account_Num" value="<?php echo get($result, "Account_Num");?>" />
        </label>
        <label for="AccountType">Account Type
            <input type="text" id="AccountType" name="Account_Type" value="<?php echo get($result, "Account_Type");?>" />
        </label>
        <label for="AccountBalance">Account Balance
            <input type="number" id="AccountBalance" name="Account Balance" value="<?php echo get($result, "Account_Balance");?>" />
        </label>
        <?php if($AccountNum > 0):?>
            <input type="submit" name="updated" value="Update Thing"/>
            <input type="submit" name="delete" value="Delete Thing"/>
        <?php elseif ($AccountNum < 0):?>
            <input type="submit" name="created" value="Create Thing"/>
        <?php endif;?>
    </form>

<?php
if(isset($_POST["updated"]) || isset($_POST["created"]) || isset($_POST["delete"])){
    $delete = isset($_POST["delete"]);
    $name = $_POST["Name"];
    $AccountNum = $_POST["Account_Number"];
    $AccountType = $_POST["Account_Type"];
    $AccountBalance = $_POST["Account_Balance"];
    if(!empty($name) && !empty($AccountNum)&& !empty($AccountType)&& !empty($AccountBalance)){
        try{
            if($AccountNum > 0) {
                if($delete){
                    $stmt = $db->prepare("DELETE from Bank_Account where Account_Number=$AccountNum");
                    $result = $stmt->execute(array(
                        ":id" => $AccNum
                    ));
                }
                else {
                    $stmt = $db->prepare("UPDATE Bank_Account set Name='$name', Account_Type='$Acctyp', Account_Balance=$AccountBalance where Account_Number=$AccountNum");
                    $result = $stmt->execute();
                }
            }
            else{
                $stmt = $db->prepare("INSERT INTO Bank_Account (Name, Account_Number, Account_Type,Account_Balance) VALUES (:name, :AccountNum, :Acctyp,:AccountBalance)");
                $result = $stmt->execute(array(
                    ":name" => $name,
                    ":AccountNum" => $AccountNum,
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
                    echo "Successfully interacted with thing: " . $name;
                }
                else{
                    echo "Error interacting record";
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