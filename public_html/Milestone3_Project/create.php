<?php include("header.php");?>
<script src="script.js"></script>
<h2>Create Account</h2>
<form method="POST">
    <label for="name">Account Name
        <input type="text" id="Name" name="Name" />
    </label>
    <label for="acctype">Account Type
        <select id="acctype" name="acctype" >
            <option value="Checking">Checking</option>
            <option value="Savings">Savings</option>
            <option value="Loan">Loan</option>
        </select>
    </label>
    <label for="balance">Balance
        <input type="number" id="balance" name="Balance" />
    </label>
    <input type="submit" name="created" value="Create Account"/>
</form>

<?php
if(isset($_POST["created"])){
    $name = $_POST["name"];
    $balance = $_POST["balance"];
    $acctype = $_POST ["acctype"];

    if(isset($_POST["Balance"]) && !empty($_POST["Balance"])){
        if(is_numeric($_POST["Balance"])){
            $Balance = (int)$_POST["Balance"];
        }
    }
    if(empty($name) || $Balance < 0 ){
        echo "Account name and balance must not be empty. Balance should be at least 5 dollars.";
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
            echo "Failed to find insert_table_accounts.sql file";
        }
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}
?>
<?php include 'footer.php';?>
