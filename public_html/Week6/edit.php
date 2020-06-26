<?php
$AccountsId = -1;
if(isset($_GET["accountId"]) && !empty($_GET["accountId"])){
    $AccountsId = $_GET["accountId"];
}
$result = array();
require("common.inc.php");
?>
<?php
if(isset($_POST["updated"])){
    $name = "";
    $AccountBalance = -1;
    if(isset($_POST["name"]) && !empty($_POST["name"])){
        $name = $_POST["name"];
    }
    if(isset($_POST["AccountBalance"]) && !empty($_POST["AccountBalance"])){
        if(is_numeric($_POST["AccountBalance"])){
            $AccountBalance = (int)$_POST["AccountBalance"];
        }
    }
    if(!empty($name) && $AccountBalance > -1){
        try{
            $query = NULL;
            echo "[Balance" . $AccountBalance . "]";
            $query = file_get_contents(__DIR__ . "/queries/update_table_accounts.sql");
            if(isset($query) && !empty($query)) {
                $stmt = getDB()->prepare($query);
                $result = $stmt->execute(array(
                    ":name" => $name,
                    ":AccountBalance" => $AccountBalance,
                    ":id" => $balanceId
                ));
                $e = $stmt->errorInfo();
                if ($e[0] != "00000") {
                    echo var_export($e, true);
                } else {
                    if ($result) {
                        echo "Successfully updated account: " . $name;
                    } else {
                        echo "Error updating record";
                    }
                }
            }
            else{
                echo "Failed to find update_table_accounts.sql file";
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Name and Account Balance must not be empty.";
    }
}
?>

<?php
//moved the content down here so it pulls the update from the table without having to refresh the page or redirect
//now my success message appears above the form so I'd have to further restructure my code to get the desired output/layout
if($accountId > -1){
    $query = file_get_contents(__DIR__ . "/queries/select_one_table_accounts.sql");
    if(isset($query) && !empty($query)) {
        //Note: SQL File contains a "LIMIT 1" although it's not necessary since ID should be unique (i.e., one record)
        try {
            $stmt = getDB()->prepare($query);
            $stmt->execute([":id" => $accountId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Failed to find select_one_table_accounts.sql file";
    }
}
else{
    echo "No AccountsId provided in url, don't forget this or sample won't work.";
}
?>
<script src="js/script.js"></script>
<!-- note although <script> tag "can" be self terminating some browsers require the
full closing tag-->
<form method="POST"onsubmit="return validate(this);">
    <label for="account">Account Name
        <!-- since the last assignment we added a required attribute to the form elements-->
        <input type="text" id="account" name="name" value="<?php echo get($result, "name");?>" required />
    </label>
    <label for="b">Balance
        <!-- We also added a minimum value for our number field-->
        <input type="number" id="b" name="AccountBalance" value="<?php echo get($result, "AccountBalance");?>" required min="0"/>
    </label>
    <input type="submit" name="updated" value="Update Account"/>
</form>