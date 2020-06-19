<form method="POST">
    <label for="name">Account Name
        <input type="text" id="Name" name="Name" />
    </label>
    <label for="AccountNum">Account Number
        <input type="number" id="AccountNum" name="Account_Number" />
    </label>
    <label for="AccountType">Account Type
        <input type="text" id="AccountType" name="Account_Type" />
    </label>
    <label for="AccountBalance">Balance
        <input type="number" id="AccountBalance" name="Account_Balance" />
    </label>
    <input type="submit" name="Bank" value="Delete Account"/>
</form>
<?php
require("common.inc.php");
$db = getDB();
$AccountNum = $_POST["Account_Number"];
$stmt = $db->prepare("DELETE from Account where Account_Number=$AccountNum1");
$result = $stmt->execute(array(
    ":id" => $AccountNum1
));
$e = $stmt->errorInfo();
if($e[0] != "00000"){
    echo var_export($e, true);
}
else{
    //echo var_export($result, true);
    if ($result){
        echo "Successfully deleted: " . $Name;
    }
    else{
        echo "Error deleting record";
    }
}
?>