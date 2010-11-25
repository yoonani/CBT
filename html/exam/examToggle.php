<?php
/* =============================================================================
File : exam/examToggle.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	시험 상태 변경
	- $_GET["examID"]
	- $_GET["cState"]로 $_GET["examID"] 시험의 현재 상태를 받는다.
	받은 값이 "E" -> "R"로
	받은 값이 "R" -> "D"로
	받은 값이 "D" -> "E"로
	변경한다.
	변경후 exam/examList.php?myPg=<?=$_GET["myPg"]?>로 돌아간다.
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
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
	$fnc->alertBack("시험 상태를 변경할 수 없습니다");
	exit;
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>exam/examList.php?myPg=<?=$_GET["myPg"]?>">
<?
ob_end_flush();
?>
