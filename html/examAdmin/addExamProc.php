<?php
/* =============================================================================
File : examAdmin/addExamProc.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	시험 추가 : 실제 DB에 저장한다.
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


$sql = "SELECT nextval('examadmin_myid_seq') FROM examAdmin";
if(!$DB->query($sql)) {
	echo $DB->error();
//	$fnc->alertBack("쿼리를 수행할 수 없습니다.\\n잠시후에 다시 입력해 주세요");
	exit;
}
$examID = $DB->getResult(0, 0);

if(empty($examID)) {
	$examID = 1;
}


$sql = "INSERT INTO examAdmin VALUES (" . $examID. ", '" . trim($_POST["getTitle"]) . "', '" . $myStart . "', '" . $myEnd . "', 0)";
if(!$DB->query($sql)) {
//	echo $DB->error();
	$fnc->alertBack("쿼리를 수행할 수 없습니다.\\n잠시후에 다시 입력해 주세요(2)");
	exit;
}

if(!@mkdir(EXAM_INFO_PATH . $examID)) {
	$fnc->alert("시험정보 디렉토리를 만들 수 없습니다.\\n디렉토리를 확인해 주시기 바랍니다.");
}

//
// BIO ADD FOR FCKeditor FileUpload 
// Image Upload Path : IMG_PATH . "testimages/" . $examID
// Flash Upload Path : IMG_PATH . "swf/" . $examID 
//
if(!@mkdir(IMG_PATH . "testimages/" . $examID)) {
	$fnc->alert("이미지 업로드 디렉토리를 만들 수 없습니다.\\n디렉토리를 확인해 주시기 바랍니다.");
}
if(!@chmod (IMG_PATH . "testimages/" . $examID, 0755)){
	$fnc->alert("이미지 업로드 디렉토리를 권한을 변경할 수 없습니다.\\n디렉토리를 확인해 주시기 바랍니다.");
}
if(!@mkdir(IMG_PATH . "swf/" . $examID)) {
	$fnc->alert("플래시 업로드 디렉토리를 만들 수 없습니다.\\n디렉토리를 확인해 주시기 바랍니다.");
}
if(!@chmod (IMG_PATH . "swf/" . $examID, 0755)){
	$fnc->alert("플래시 업로드 디렉토리를 권한을 변경할 수 없습니다.\\n디렉토리를 확인해 주시기 바랍니다.");
}
//
// End
//
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>exam/examList.php">
<?
require_once (MY_INCLUDE . "closing.php");
?>
