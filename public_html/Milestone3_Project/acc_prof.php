<?php
include("header.php");
?>
<h2>Account Details</h2>
<form method="POST" style="padding: 20px;">
    <input type="text" name="accountId" placeholder="Account ID">
    <input type="text" name="name" placeholder="Account Name">
    <input type="hidden" name="acctype" value="<?php echo $_GET['acctype'];?>"/>

    <!--Based on sample type change the submit button display-->
    <input type="submit" value="View Account Activity"/>
</form>

<?php
$email=$_SESSION["user"]["email"];
$account=$_GET["account"];
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$stmt = $db->prepare("SELECT count(*) as num FROM Transactions where acc_dest_id=:acc");
$stmt->execute(array(
    ":acc" => $account
));
$res = $stmt->fetchAll();
$e = $stmt->errorInfo();
if($e[0] != "00000"){
    var_dump($e);
    echo "setting eee ".$e."<br>";
}
$stmt1 = $db->prepare("SELECT * FROM Accounts where acc_num=:acc");
$stmt1->execute(array(
    ":acc" => $account
));
$result = $stmt1->fetchAll();
$e = $stmt1->errorInfo();
if($e[0] != "00000"){
    var_dump($e);
    echo "setting eee ".$e."<br>";
}
$acctype=$result[0]["acctype"];
$amount=$result[0]["Balance"];

?>

<input type="date" placeholder="From Date" id="post_at" name="post_at" style="margin-top: 10px"/>
<input type="date" placeholder="To Date" id="post_at_to_date" name="post_at_to_date" style="margin-left:25px"    />

<br>
<select name="types" id="types">
    <option value=""></option>
    <?php
    foreach($res as $item){
        ?>
        <option value="<?php echo strtolower($item["num"]); ?>"><?php echo $item["num"]; ?></option>
        <?php
    }
    ?>
</select>
<button onclick="showRecords(2,1)">Search</button>
<script
    src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<div id="container">
    <div id="inner-container">
        <div id="results"></div>
        <div id="loader"></div>
    </div>
</div>
<script type="text/javascript">
    function showRecords(perPageCount, pageNumber) {
        var acc = "<?php echo $account; ?>";
        var post_at = document.getElementById("post_at").value;
        var post_at_to_date = document.getElementById("post_at_to_date").value;
        var e = document.getElementById("types");
        var strUser = e.options[e.selectedIndex].value;
        $.ajax({
            type: "GET",
            url: "pages.php",
            data: {"pageNumber": pageNumber,"account": acc, "datefrom": post_at, "dateto": post_at_to_date, "acctype": strUser},
            cache: false,
            beforeSend: function() {
                $('#loader').html('<img src="loader.png" alt="reload" width="20" height="20" style="margin-top:10px;">');

            },
            success: function(html) {
                $("#results").html(html);
                $('#loader').html('');
            }
        });
    }

    $(document).ready(function() {
        $( "#post_at" ).datepicker();
        $( "#post_at_to_date" ).datepicker();
        showRecords(2, 1);
    });
</script>
