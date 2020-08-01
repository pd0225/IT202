<?php
include("header.php");
?>
    <h2>Delete Account</h2>
    <form method="POST">
        <label for="acc_num">Account Number
            <input type="number" id="acc_num" name="acc_num" />
        </label>
        <input type="submit" name="Bank" value="Delete Account"/>
    </form>

<?php
if (isset($_GET["accountId"]) && !empty($_GET["accountId"])){
    if(is_numeric($_GET["accountId"])){
        $accountId = (int)$_GET["accountId"];
        $query = file_get_contents(__DIR__ . "/queries/delete_one_table_accounts.sql");
        if(isset($query) && !empty($query)) {
            require("common.inc.php");
            $stmt = getDB()->prepare($query);
            $stmt->execute([":id"=>$accountId]);
            $e = $stmt->errorInfo();
            if($e[0] == "00000"){
                //we're just going to redirect back to the list
                //it'll reflect the delete on reload
                //also wrap it in a die() to prevent the script from any continued execution
                die(header("Location: list.php"));
            }
            else{
                echo var_export($e, true);
            }
        }
    }
}
else{
    echo "Invalid account to delete";
}
?>

<?php include 'footer.php';?>
