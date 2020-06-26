<?php
if (isset($_GET["AccountsId"]) && !empty($_GET["AccountsId"])){
    if(is_numeric($_GET["AccountsId"])){
        $accountId = (int)$_GET["AccountsId"];
        $query = file_get_contents(__DIR__ . "/queries/delete_one_table_accounts.sql");
        if(isset($query) && !empty($query)) {
            require("common.inc.php");
            $stmt = getDB()->prepare($query);
            $stmt->execute([":id"=>$AccountsId]);
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