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
print_r($_POST);
echo "<BR>";
$myText = trim($_POST["getContents"]);
$myImage = $_FILES["getImage"]["name"];
$myMovie = $_FILES["getMovie"]["name"];
$sql = "SELECT nextval('itemgrouptable_myid_seq')";
if(!$DB->query($sql)) {
	$fnc->alertBack("Group ���� ������ ������ �� �����ϴ�.");
	exit;
}
$nextID = $DB->getResult(0, 0);
echo $nextID;
if(!$fnc->beginTrans($DB)) {
	$fnc->alertBack("Transaction�� ������ �� �����ϴ�.");
	exit;
}
$sql = "INSERT INTO ItemGroupTable VALUES (" . $nextID . ", '" . $myText . "', 'N', '" . $myImage . "', '" . $myMovie . "')";
if(!$DB->query($sql)) {
	$fnc->beginRollback($DB);
	$fnc->alertBack("Query�� ������ �� �����ϴ�.");
	exit;
}

$sql = "INSERT INTO ExamGroup VALUES (nextval('examgroup_myid_seq'), " . $_POST["examID"] . ", " . $nextID . ")";

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
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$_POST["examID"]?>">
<?
require_once (MY_INCLUDE . "closing.php");
?>
