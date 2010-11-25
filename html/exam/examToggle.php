<?php
/* =============================================================================
File : exam/examToggle.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	���� ���� ����
	- $_GET["examID"]
	- $_GET["cState"]�� $_GET["examID"] ������ ���� ���¸� �޴´�.
	���� ���� "E" -> "R"��
	���� ���� "R" -> "D"��
	���� ���� "D" -> "E"��
	�����Ѵ�.
	������ exam/examList.php?myPg=<?=$_GET["myPg"]?>�� ���ư���.
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
switch(trim($_GET["cState"])) {
	case "E" :
		$toStr = "R";
		break;
	case "R" :
		$toStr = "D";
		break;
	case "D" :
		$toStr = "E";
		break;
}

$sql = "UPDATE examAdmin SET myStatus = '" . $toStr . "' WHERE myID = " . $_GET["examID"];
if(!$DB->query($sql)) {
	$fnc->alertBack("���� ���¸� ������ �� �����ϴ�");
	exit;
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>exam/examList.php?myPg=<?=$_GET["myPg"]?>">
<?
ob_end_flush();
?>
