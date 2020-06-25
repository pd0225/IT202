<script src="js/script.js"></script>
<!-- note although <script> tag "can" be self terminating some browsers require the
full closing tag-->
<form method="POST" onsubmit="return validate(this);">
    <label for="Accounts">Account Name
        <input type="text" id="Accounts" name="name" required />
    </label>
    <label for="b">AccountBalance
        <input type="number" id="b" name="AccountBalance" required min="1" />
    </label>
    <input type="submit" name="created" value="Create Account"/>
</form>
<?php
if(isset($_POST["created"])) {
    $name = "";
    $AccountBalance = -1;
    if(isset($_POST["name"]) && !empty($_POST["name"])){
        $name = $_POST["name"];
    }
    if(isset($_POST["AccountBalance"]) && !empty($_POST["AccountBalance"])){
        if(is_numeric($_POST["AccountBalance"])){
            $quantity = (int)$_POST["AccountBalance"];
        }
    }
    //If name or AccountBalance is invalid, don't do the DB part
    if(empty($name) || $AccountBalance < 0 ){
        echo "Name must not be empty and Account Balance must be greater than or equal to 1";
        die();//terminates the rest of the script
    }
    try {
        require("common.inc.php");
        $query = file_get_contents(__DIR__ . "/queries/insert_table_accounts.sql");
        if(isset($query) && !empty($query)) {
            $stmt = getDB()->prepare($query);
            $result = $stmt->execute(array(
                ":name" => $name,
                ":AccountBalance" => $AccountBalance
            ));
            $e = $stmt->errorInfo();
            if ($e[0] != "00000") {
                echo var_export($e, true);
            } else {
                if ($result) {
                    echo "Successfully added new account: " . $name;
                } else {
                    echo "Error inserting record";
                }
            }
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