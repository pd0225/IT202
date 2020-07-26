<?php
if (! (isset($_GET['pagenum']))) {
    $pagenum = 1;
} else {
    $pageum = $_GET['pagenum'];
}

$PageCount = 2;

require("config.php");
$email=$_SESSION["user"]["email"];
$account=$_GET["account"];
$post_at=$_GET["datefrom"];
$post_at_to_date=$_GET["dateto"];
$acctype=$_GET["acctype"];
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";
$db = new PDO($connection_string, $dbuser, $dbpass);
$stmt = $db->prepare("SELECT count(*) as num FROM Transactions where acc_src_id=:acc");
if (!empty($post_at) && !empty($post_at_to_date)) {
    $stmt = $db->prepare("SELECT count(*) as num FROM Transactions where acc_src_id=:acc and Created >= '".$post_at."' and Created<= '".$post_at_to_date."'");
}
else if (!empty($acctype)) {
    $stmt = $db->prepare("SELECT count(*) as num FROM Transactions where acc_src_id=:acc and acctype = '".$acctype."'");
}

$stmt->execute(array(
    ":acc" => $account
));
$res = $stmt->fetchAll();

$rowCount=$res[0]["num"];
$pagesCount = ceil($rowCount / $PageCount);

$lowerLimit = ($pagenum - 1) * $PageCount;

$stmt = $db->prepare("SELECT * FROM Transactions  where acc_src_id=:acc limit " . ($lowerLimit) . " ,  " . ($PageCount) . " ");
if (!empty($post_at) && !empty($post_at_to_date)) {
    $stmt = $db->prepare("SELECT * FROM Transactions where acc_src_id=:acc and Created >= '".$post_at."' and Created<= '".$post_at_to_date."' limit ". ($lowerLimit) . " ,  " . ($PageCount) . " ");

}
else if(!empty($acctype)){
    $stmt = $db->prepare("SELECT * FROM Transactions where acc_src_id=:acc and Type= '".$acctype."' limit ". ($lowerLimit) . " ,  " . ($PageCount) . " ");

}
$stmt->execute(array(
    ":acc" => $account
));
$results = $stmt->fetchAll();

?>

<table class="table table-hover table-responsive">
    <tr>
        <th align="center">From</th>
        <th align="center">To</th>
        <th align="center">Type</th>
        <th align="center">Amount</th>
        <th align="center">Date</th>
    </tr>
    <?php foreach ($results as $data) { ?>
        <tr>
            <td align="left">
                <?php echo $data['acc_src_id'] ?>
            </td>
            <td align="left">
                <?php echo $data['acc_dest_id'] ?>
            </td>
            <td align="left">
                <?php echo $data['acctype'] ?>
            </td>
            <td align="left">
                <?php echo $data['exp_total'] ?>
            </td>
            <td align="left">
                <?php echo $data['Created'] ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<div style="height: 30px;"></div>
<table width="50%" align="left" >
    <tr>

        <td valign="top" align="left"></td>


        <td valign="top" align="center">
            <?php
            for ($i = 1; $i <= $pagesCount; $i ++) {
                if ($i == $pagenum) {
                    ?> <a href="javascript:void(0);" class="current">
                        <?php echo $i ?>
                    </a> <?php
                } else {
                    ?> <a href="javascript:void(0);" class="pages"
                          onclick="showRecords('<?php echo $PageCount;  ?>', '<?php echo $i; ?>');">
                        <?php echo $i ?>
                    </a> <?php
                } // endIf
            } // endFor

            ?>
        </td>
        <td align="right" valign="top">Page <?php echo $pagenum; ?>
            of <?php echo $pagesCount; ?>
        </td>
    </tr>
</table>