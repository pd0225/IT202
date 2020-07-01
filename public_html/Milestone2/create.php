<form method="POST" onsubmit="return validate(this);">
    <label for="account">Account Name
        <input type="text" id="account" name="name" required />
    </label>
    <label for="b">Balance
        <input type="number" id="b" name="balance" required min="0" />
    </label>
    <input type="submit" name="created" value="Create Account"/>
</form>
<?php
if(isset($_POST["created"])) {
    $name = "";
    $balance = -1;
    if(isset($_POST["name"]) && !empty($_POST["name"])){
        $name = $_POST["name"];
    }
    if(isset($_POST["AccountBalance"]) && !empty($_POST["AccountBalance"])){
        if(is_numeric($_POST["AccountBalance"])){
            $AccountBalance = (int)$_POST["AccountBalance"];
        }
    }
    //If name or balance is invalid, don't do the DB part
    if(empty($name) || $AccountBalance < 0 ){
        echo "Name must not be empty and account balance must be greater than or equal to 0";
        die();//terminates the rest of the script
    }
    try {
        require("common.inc.php");
        $query = file_get_contents(__DIR__ . "/queries/insert_table_accounts.sql");
        if(isset($query) && !empty($query)) {
            $stmt = getDB()->prepare($query);
            $result = $stmt->execute(array(
                ":name" => $name,
                ":balance" => $balance
            ));
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "Successfully inserted new account: " . $name;
                } else {
                    echo "Error inserting record";
                }
            }
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