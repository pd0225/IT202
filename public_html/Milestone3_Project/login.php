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
if (Common::get($_POST, "submit", false)){
    $email = Common::get($_POST, "email", false);
    $password = Common::get($_POST, "password", false);
    if(!empty($email) && !empty($password)){
        $result = DBH::login($email, $password);
        echo var_export($result, true);
        if(Common::get($result, "status", 400) == 200){
            $_SESSION["user"] = Common::get($result, "data", NULL);
            die(header("Location: " . Common::url_for("home")));
        }
        else{
            Common::flash(Common::get($result, "message", "Error logging in"));
            die(header("Location: " . Common::url_for("login")));
        }
    }
    else{
        Common::flash("Email and password should not be empty", "warning");
        die(header("Location: " . Common::url_for("login")));
    }
}
?>