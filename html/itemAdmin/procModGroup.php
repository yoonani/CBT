<?php
/* =============================================================================
File : itemAdmin/procAddGroup.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	새로운 문항 Group을 추가한다.
	입력 Table(순서대로)
	- ItemGroupTable
	- ExamGroup
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
$myText = trim($_POST["getContents"]);
if(!$fnc->beginTrans($DB)) {
	$fnc->alertBack("Transaction을 시작할 수 없습니다.");
	exit;
}
$sql = "UPDATE itemgrouptable SET mytext = '" . $myText . "' WHERE myid = " . $_POST["myGrpID"];
echo $sql;
if(!$DB->query($sql)) {
	$fnc->beginRollback($DB);
	$fnc->alertBack("Query를 수행할 수 없습니다.");
	exit;
}

if(!$fnc->commitTrans($DB)) {
	$fnc->beginRollback($DB);
	$fnc->alertBack("Query를 수행할 수 없습니다.");
	exit;
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$_POST["examID"]?>&myPg=<?=$_POST["myPg"]?>">
<?
require_once (MY_INCLUDE . "closing.php");
?>
