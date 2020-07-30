<?php
require("config.php");
include("header.php");
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$acc_num = -1;
$result = array();
$email=$_GET["email"];
$pwdreset=$_GET["resetpassword"];
if(!empty($pwdreset)){
}
if(empty($email)){
    $email=$_SESSION["user"]["email"];
    $first_name=$_SESSION["user"]["first_name"];
    $last_name=$_SESSION["user"]["last_name"];
    $id=$_SESSION["user"]["id"];
}
else{
    $stmt = $db->prepare("SELECT first_name, last_name, Id from User where email='$email'");
    $stmt->execute();
    $result = $stmt->fetchAll();
    $first_name=$result[0]["First_name"];
    $last_name=$result[0]["Last_name"];
    $id=$result[0]["Id"];
}

function get($arr, $key){
    if(isset($arr[$key])){
        return $arr[$key];
    }
    return "";
}
?>

    <form method="POST">
        <label for="email">email
            <input type="text" id="email" name="email" value="<?php echo $email;?>" />
        </label>
        <label for="first_name">first_name
            <input type="text" id="first_name" name="first_name" value="<?php echo $first_name;?>" />
        </label>
        <label for="last_name">last_name
            <input type="text" id="last_name" name="last_name" value="<?php echo $last_name;?>" />
        </label>
        <label for="password" <?php if(!empty($pwdreset)) echo 'style="visibility:hidden;"'?>>Password
            <input type="text" id="password" name="password" style="visibility:hidden;" value="<?php echo get($result, "password");?>" />
        </label><br>
        <input type="submit" name="updated" value="Update"/>
    </form>
<?php
//echo "before update/create check";
if(isset($_POST["updated"]) || isset($_POST["created"])){
    //echo "after create/update check";
    $mail = $_POST["email"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $password = $_POST["password"];

    $hash=password_hash($password, PASSWORD_BCRYPT);

    if(!empty($email) || !empty($Fname) || !empty($Lname) || !empty($password)){
        try{
            echo "in try block";
            $stmt = $db->prepare("SELECT count(*) as num from User where email='$email'");
            //$stmt = $db->prepare("UPDATE User set email='$email', First_name='$Fname', Last_name='$Lname', password='$hash' where Id=1");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $num=$result[0]["num"];
            if($num>0 && $mail!=$email){
                $error = 'Email Already in use';
                throw new Exception($error);
            }

            if($num==0 && $mail!=$email){
                $str="UPDATE User set email='$mail', first_name='$first_name', last_name='$last_name'";
            }
            else $str="UPDATE User set first_name='$first_name', last_name='$last_name'";
            if(!empty($password) && !empty($pwdreset) ){
                $str=$str.", password='$hash'";
            }
            $str=$str." where Id=$id";
            $stmt = $db->prepare($str);
            $stmt->execute();


            $e = $stmt->errorInfo();
            if($e[0] != "00000"){
                echo "try 1 if";
                echo var_export($e, true);
            }
            else{
                echo var_export($result, true);
                if ($result){
                    $_SESSION["user"]["email"]=$email;
                    $_SESSION["user"]["first_name"]=$first_name;
                    $_SESSION["user"]["last_name"]=$last_name;
                    echo var_export($_SESSION, true);
                    echo "Successfully inserted or updated: " . $email;
                    header("Location: home.php");
                }
                else{
                    echo "Error inserting or updating record";
                }
            }
        }
        catch (Exception $e){
            echo "in catch block";
            echo $e->getMessage();
        }
    }
    else{
        var_dump($email);
        var_dump($first_name);
        var_dump($last_name);
        var_dump($password);
        echo "Name and quantity must not be empty.";
    }
}
?>