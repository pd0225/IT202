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
    if(!$result){
        $accountId = -1;
    }
}
else{
    echo "No accountId provided in url.";
}
?>

    <form method="POST">
        <label for="account">Account Name
            <input type="text" id="account" name="name" value="<?php echo get($result, "name");?>" />
        </label>
        <label for="b">Balance
            <input type="number" id="b" name="AccountBalance" value="<?php echo get($result, "AccountBalance");?>" />
        </label>
        <?php if($accountId > 0):?>
            <input type="submit" name="updated" value="Update Account"/>
        <?php elseif ($accountId < 0):?>
            <input type="submit" name="created" value="Create Account"/>
        <?php endif;?>
    </form>

<?php
if(isset($_POST["updated"]) || isset($_POST["created"])){
    $name = $_POST["name"];
    $AccountBalance = $_POST["AccountBalance"];
    if(!empty($name) && !empty($AccountBalance)){
        try{
            if($AccountId > 0) {
                $stmt = $db->prepare("UPDATE Account set name = :name, AccountBalance=:AccountBalance where id=:id");
                $result = $stmt->execute(array(
                    ":name" => $name,
                    ":AccountBalance" => $AccountBalance,
                    ":id" => $accountId
                ));
            }
            else{
                $stmt = $db->prepare("INSERT INTO Account (name, AccountBalance) VALUES (:name, :AccountBalance)");
                $result = $stmt->execute(array(
                    ":name" => $name,
                    ":AccountBalance" => $AccountBalance
                ));
            }
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
                    echo "Error inserting or updating account";
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