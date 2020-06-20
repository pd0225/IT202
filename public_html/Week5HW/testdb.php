<?php
require("config.php");

$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
try{
	$db = new PDO($connection_string, $dbuser, $dbpass);
	$stmt = $db->prepare("CREATE TABLE `Account` (
				id int auto_increment,
                AccountNum varchar(12) NOT NULL,
                user_id int,
                AccountType varchar(20),
                OpenedDate DATETIME default CURRENT_TIMESTAMP
                AccountBalance decimal(12,2) default 0.00,
                primary key (id)
                );
	$r = $stmt->execute();
	echo var_export($stmt->errorInfo(), true);
	echo var_export($r, true);
}
catch (Exception $e){
	echo $e->getMessage();
}
?>