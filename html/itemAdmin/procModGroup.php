<?php
/* =============================================================================
File : itemAdmin/procAddGroup.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	���ο� ���� Group�� �߰��Ѵ�.
	�Է� Table(�������)
	- ItemGroupTable
	- ExamGroup
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
$myText = trim($_POST["getContents"]);
if(!$fnc->beginTrans($DB)) {
	$fnc->alertBack("Transaction�� ������ �� �����ϴ�.");
	exit;
}
$sql = "UPDATE itemgrouptable SET mytext = '" . $myText . "' WHERE myid = " . $_POST["myGrpID"];
echo $sql;
if(!$DB->query($sql)) {
	$fnc->beginRollback($DB);
	$fnc->alertBack("Query�� ������ �� �����ϴ�.");
	exit;
}

if(!$fnc->commitTrans($DB)) {
	$fnc->beginRollback($DB);
	$fnc->alertBack("Query�� ������ �� �����ϴ�.");
	exit;
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$_POST["examID"]?>&myPg=<?=$_POST["myPg"]?>">
<?
require_once (MY_INCLUDE . "closing.php");
?>
