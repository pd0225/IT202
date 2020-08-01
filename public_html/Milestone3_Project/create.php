<?php include("header.php");?>
<script src="script.js"></script>

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

<h2>Create Account</h2>

<?php
if(isset($_POST["created"])){
    $name = $_POST["name"];
    $balance = $_POST["balance"];
    $acctype = $_POST ["acctype"];
    if(!empty($name) && !empty($balance)){
        require("config.php");
        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        try{
            $db = new PDO($connection_string, $dbuser, $dbpass);
            $stmt = $db->prepare("INSERT INTO Accounts (name, balance, acctype) VALUES (:name, :balance, :acctype)");
            $result = $stmt->execute(array(
                ":name" => $name,
                ":balance" => $balance,
                ":acctype" => $acctype
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                echo var_export($result, true);
                if ($result){
                    echo "Successfully created new account: " . $name;
                }
                else{
                    echo "Error creating account";
                }
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        echo "Account name and balance must not be empty. Balance must be at least 5 dollars.";
    }
}
?>
<?php include 'footer.php';?>
