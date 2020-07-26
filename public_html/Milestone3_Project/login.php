<?php
include("header.php");
?>
<h2>Login</h2>
<form method="POST">
	<label for="email">Email
	<input type="email" id="email" name="email" autocomplete="off" />
	</label>
	<label for="p">Password
	<input type="password" id="p" name="password" autocomplete="off"/>
	</label>
	<input type="submit" name="login" value="Login"/>
    <input type="button" class="submit" onclick="window.location.href='password.php'"
           value="Change Password"/>
</form>

<?php
if(isset($_POST["login"])){
    if(isset($_POST["password"]) && isset($_POST["email"])){
        $password = $_POST["password"];
        $email = $_POST["email"];
        //require("config.php");
        $connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
        try{
            $db = new PDO($connection_string, $dbuser, $dbpass);
            $stmt = $db->prepare("SELECT * FROM Users where email = :email LIMIT 1");
            $stmt->execute(array(
                ":email" => $email
            ));
            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo var_export($e, true);
            }
            else{
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result){
                    $rpassword = $result["password"];
                    if(password_verify($password, $rpassword)){
                        echo "<div>Passwords matched, you are technically logged in!</div>";
                        $_SESSION["user"] = array(
                            "id"=>$result["Id"],
                            "email"=>$result["email"],
                            "first_name"=>$result["first_name"],
                            "last_name"=>$result["last_name"]
                        );
                        $query=$db->prepare("SELECT acc_num FROM Accounts, Users a where id=User_id and email=:email");

                        $query->execute(array(
                            ":email" => $email
                        ));
                        $res = $query->fetchAll();
                        $_SESSION["user"]["accounts"]=$res;
                        echo var_export($_SESSION, true);
                        header("Location: home.php");
                    }
                    else{
                        echo "<div>Invalid password</div>";
                    }
                }
                else{
                    echo "<div>Invalid user</div>";
                }
            }
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
}
?>