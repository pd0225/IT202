<?php
require("config.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$accountId = -1;
$result = array();
function get($arr, $key){
    if(isset($arr[$key])){
        return $arr[$key];
    }
    return "";
}
if(isset($_GET["accountId"])){
    $accountId = $_GET["accountId"];
    $stmt = $db->prepare("SELECT * FROM Account where id = :id");
    $stmt->execute([":id"=>$accountId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
else{
    echo "No accountId provided in url.";
}
?>

    <form method="POST">
        <label for="AccountName">Account Name
            <input type="text" id="account" name="name" value="<?php echo get($result, "AccountName");?>" />
        </label>
        <label for="b">Account Balance
            <input type="number" id="b" name="balance" value="<?php echo get($result, "AccountBalance");?>" />
        </label>
        <input type="submit" name="updated" value="Update Account"/>
    </form>

<?php
if(isset($_POST["updated"])){
    $name = $_POST["name"];
    $balance = $_POST["balance"];
    if(!empty($name) && !empty($deposit)){
        try{
            $stmt = $db->prepare("UPDATE Account set name = :AccountName, balance=:AccountBalance where id=:id");
            $result = $stmt->execute(array(
                ":AccountName" => $AccountName,
                ":AccountBalance" => $AccountBalance,
                ":id" => $accountId
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                echo var_export($result, true);
                if ($result){
                    echo "Successfully updated account: " . $AccountName;
                }
                else{
                    echo "Error updating account";
                }
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Name and balance must not be empty.";
    }
}
?>