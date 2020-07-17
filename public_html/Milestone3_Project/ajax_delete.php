<?php
$result = array("status"=>200, "message"=>"Nothing happened");
if (isset($_GET["AccountsId"]) && !empty($_GET["AccountsId"])){
    if(is_numeric($_GET["AccountsId"])){
        $AccountsId = (int)$_GET["AccountsId"];
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
                $result["message"] = "Successfully deleted account";
            }
            else{
                $result["message"] = "Error deleting account";
                $result["error"] = var_export($e,true);
                $result["status"] = 400;
            }
        }
    }
}
else{
    $result["message"] = "Invalid account to delete";
    $result["status"] = 400;
}
echo json_encode($result);
?>