<?php
/* =============================================================================
File : examAdmin/modifyExamProc.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2008. 10. 
================================================================================
Dsec.
	접근가능 : A
	시험 변경 : 시험명, 시간등을 변경한다.
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}

if($fv->lengthlt($_POST["getTitle"], 1)) {
	$fnc->alertBack("시험명을 입력하세요");
	exit;
}
$myStartUT = mktime($_POST["getHour"], $_POST["getMinute"], 00, $_POST["getMonth"], $_POST["getDay"], $_POST["getYear"]);
$myEndUT = mktime($_POST["getHour2"], $_POST["getMinute2"], 00, $_POST["getMonth2"], $_POST["getDay2"], $_POST["getYear2"]);

if($myEndUT <= $myStartUT) {
	$fnc->alertBack("시간을 확인해 주세요\\n종료시간이 시작시간보다 빠릅니다.");
	exit;
}

$myStart = $_POST["getYear"] . "-" . $_POST["getMonth"] . "-" . $_POST["getDay"] . " " . $_POST["getHour"] . ":" . $_POST["getMinute"] . ":00";
$myEnd = $_POST["getYear2"] . "-" . $_POST["getMonth2"] . "-" . $_POST["getDay2"] . " " . $_POST["getHour2"] . ":" . $_POST["getMinute2"] . ":00";


$sql = "UPDATE examAdmin SET mytitle = '" . trim($_POST["getTitle"]) . "', mystart = '" . $myStart . "', myend = '" . $myEnd . "' WHERE myID = " . $_POST["examID"];
if(!$DB->query($sql)) {
//	echo $DB->error();
	$fnc->alertBack("쿼리를 수행할 수 없습니다.\\n잠시후에 다시 입력해 주세요");
	exit;
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>exam/examList.php">
<?
require_once (MY_INCLUDE . "closing.php");
?>
