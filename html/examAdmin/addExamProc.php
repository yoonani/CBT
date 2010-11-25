<?php
/* =============================================================================
File : examAdmin/addExamProc.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	���� �߰� : ���� DB�� �����Ѵ�.
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


$sql = "SELECT nextval('examadmin_myid_seq') FROM examAdmin";
if(!$DB->query($sql)) {
	echo $DB->error();
//	$fnc->alertBack("������ ������ �� �����ϴ�.\\n����Ŀ� �ٽ� �Է��� �ּ���");
	exit;
}
$examID = $DB->getResult(0, 0);

if(empty($examID)) {
	$examID = 1;
}


$sql = "INSERT INTO examAdmin VALUES (" . $examID. ", '" . trim($_POST["getTitle"]) . "', '" . $myStart . "', '" . $myEnd . "', 0)";
if(!$DB->query($sql)) {
//	echo $DB->error();
	$fnc->alertBack("������ ������ �� �����ϴ�.\\n����Ŀ� �ٽ� �Է��� �ּ���(2)");
	exit;
}

if(!@mkdir(EXAM_INFO_PATH . $examID)) {
	$fnc->alert("�������� ���丮�� ���� �� �����ϴ�.\\n���丮�� Ȯ���� �ֽñ� �ٶ��ϴ�.");
}

//
// BIO ADD FOR FCKeditor FileUpload 
// Image Upload Path : IMG_PATH . "testimages/" . $examID
// Flash Upload Path : IMG_PATH . "swf/" . $examID 
//
if(!@mkdir(IMG_PATH . "testimages/" . $examID)) {
	$fnc->alert("�̹��� ���ε� ���丮�� ���� �� �����ϴ�.\\n���丮�� Ȯ���� �ֽñ� �ٶ��ϴ�.");
}
if(!@chmod (IMG_PATH . "testimages/" . $examID, 0755)){
	$fnc->alert("�̹��� ���ε� ���丮�� ������ ������ �� �����ϴ�.\\n���丮�� Ȯ���� �ֽñ� �ٶ��ϴ�.");
}
if(!@mkdir(IMG_PATH . "swf/" . $examID)) {
	$fnc->alert("�÷��� ���ε� ���丮�� ���� �� �����ϴ�.\\n���丮�� Ȯ���� �ֽñ� �ٶ��ϴ�.");
}
if(!@chmod (IMG_PATH . "swf/" . $examID, 0755)){
	$fnc->alert("�÷��� ���ε� ���丮�� ������ ������ �� �����ϴ�.\\n���丮�� Ȯ���� �ֽñ� �ٶ��ϴ�.");
}
//
// End
//
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>exam/examList.php">
<?
require_once (MY_INCLUDE . "closing.php");
?>
