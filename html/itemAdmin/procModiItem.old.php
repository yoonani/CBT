<?
$useItemInfo = "Y";
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("접근할 수 없는 권한입니다.");
        exit;
}

$itemID = trim($_POST['itemID']);
$isIndep = trim($_POST['isIndep']);
$examID = trim($_POST['examID']);
$myPg = trim($_POST['myPg']);
$myGrpID = trim($_POST['myGrpID']);
$contents = trim($_POST['getContents']);

if($_POST["isIndep"] == "Y") {
	$sql1 = "SELECT c.myID FROM (ItemTable as a JOIN ItemAInfoTable as d ON (a.myID = d.itemID)) , ItemGInfoTable as b, ItemGroupTable as c WHERE a.myID = b.itemID AND b.groupID = c.myID AND a.myID = " . $itemID;
	$subsql1= "ItemGroupTable set myText = '";
	$subsql2= "' where myID = ";
} else {
        $sql1 = "SELECT a.myID FROM ItemTable as a JOIN ItemAInfoTable as d ON (a.myID = d.itemID) WHERE a.myID = " . $itemID;
	$subsql1= "ItemTable set myTest = '";
	$subsql2= "' where myID = ";
}
if(!$DB->query($sql1)) {
        $fnc->alertBack("문항 정보를 수정할 수 없습니다.");
        exit;
}
$row = $DB->fetch();
$myID = trim($row[0]);
$sql2 = "update " . $subsql1 . $contents . $subsql2 . $myID ;
if(!$DB->query($sql2)) {
        $fnc->alertBack("문항 정보를 수정할 수 없습니다.");
        exit;
}else{
	echo "<meta http-equiv='Refresh' content='0 ; URL=" . URL_ROOT .  "/itemAdmin/viewItem.php?examID=" . $examID . "&myPg=" . $myPg . "&itemID=" . $itemID . "&myGrpID=" . $myGrpID . "&isIndep=" . $isIndep . "'>";
}
?>
