<?php
/* =============================================================================
File : examAdmin/modifyExamProc.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2008. 10. 
================================================================================
Dsec.
	���ٰ��� : A
	���� ���� : �����, �ð����� �����Ѵ�.
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}

if($fv->lengthlt($_POST["getTitle"], 1)) {
	$fnc->alertBack("������� �Է��ϼ���");
	exit;
}
$myStartUT = mktime($_POST["getHour"], $_POST["getMinute"], 00, $_POST["getMonth"], $_POST["getDay"], $_POST["getYear"]);
$myEndUT = mktime($_POST["getHour2"], $_POST["getMinute2"], 00, $_POST["getMonth2"], $_POST["getDay2"], $_POST["getYear2"]);

if($myEndUT <= $myStartUT) {
	$fnc->alertBack("�ð��� Ȯ���� �ּ���\\n����ð��� ���۽ð����� �����ϴ�.");
	exit;
}

$myStart = $_POST["getYear"] . "-" . $_POST["getMonth"] . "-" . $_POST["getDay"] . " " . $_POST["getHour"] . ":" . $_POST["getMinute"] . ":00";
$myEnd = $_POST["getYear2"] . "-" . $_POST["getMonth2"] . "-" . $_POST["getDay2"] . " " . $_POST["getHour2"] . ":" . $_POST["getMinute2"] . ":00";


$sql = "UPDATE examAdmin SET mytitle = '" . trim($_POST["getTitle"]) . "', mystart = '" . $myStart . "', myend = '" . $myEnd . "' WHERE myID = " . $_POST["examID"];
if(!$DB->query($sql)) {
//	echo $DB->error();
	$fnc->alertBack("������ ������ �� �����ϴ�.\\n����Ŀ� �ٽ� �Է��� �ּ���");
	exit;
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>exam/examList.php">
<?
require_once (MY_INCLUDE . "closing.php");
?>
