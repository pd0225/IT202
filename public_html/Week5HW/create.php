<form method="POST">
    <label for="account">Account Name
        <input type="text" id="account" name="name" />
    </label>
    <label for="b">Balance
        <input type="number" id="b" name="AccountBalance" />
    </label>
    <input type="submit" name="created" value="Create Account"/>
</form>

<?php
if(isset($_POST["created"])){
    $name = $_POST["name"];
    $AccountBalance = $_POST["AccountBalance"];
    if(!empty($name) && !empty($AccounBalance)){
        require("config.php");
        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        try{
            $db = new PDO($connection_string, $dbuser, $dbpass);
            $stmt = $db->prepare("INSERT INTO Account (name, AccountBalance) VALUES (:name, :AccountBalance)");
            $result = $stmt->execute(array(
                ":name" => $name,
                ":AccountBalance" => $AccountBalance
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
        echo "Name and balance must not be empty.";
    }
}
?>