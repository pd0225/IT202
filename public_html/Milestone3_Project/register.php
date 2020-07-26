<?php include("header.php");?>
<h2>Register</h2>
<form method="POST">
	<label for="email">Email
	<input type="email" id="email" name="email" autocomplete="off" />
	</label>
	<label for="p">Password
	<input type="password" id="p" name="password" autocomplete="off" />
	</label>
	<label for="cp">Confirm Password
	<input type="password" id="cp" name="cpassword"/>
	</label>
	<input type="submit" name="register" value="Register"/>
</form>

<?php
if (Common::get($_POST, "submit", false)){
    $email = Common::get($_POST, "email", false);
    $password = Common::get($_POST, "password", false);
    $cpassword = Common::get($_POST, "cpassword", false);
    if($password != $cpassword){
        Common::flash("Passwords should match.", "warning");
        die(header("Location: register.php"));
    }
    if(!empty($email) && !empty($password) && !empty($cpassword)){
        $result = DBH::register($email, $password);
        echo var_export($result, true);
        if(Common::get($result, "status", 400) == 200){
            echo "Successfully Registered!";
            //die(header("Location: " . Common::url_for("login")));
        }
    }
    else{
        echo "Email and password must not be empty.";
        die(header("Location: register.php"));
    }
}