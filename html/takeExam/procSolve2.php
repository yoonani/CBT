<?php
/* =============================================================================
File : takeExam/procSolve.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	접근가능 : A, B, F
	문제 답안 제출
	- $_POST["smtOption"] | $_POST["smtOption"][]
	- $_POST["examID"]
	- $_POST["myItem"]
	- $_POST["getOptType"]
	- $_POST["getOptNo"]
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
?>
<SCRIPT LANGUAGE="JavaScript">
//	alert(parent.name);
</SCRIPT>
<?
// exit;
$sql = "SELECT myCorrect FROM ItemTable WHERE myID = " . $_POST["myItem"];
if(!$DB->query($sql)) {
	$fnc->alertBack("정답을 가져올 수 없습니다.");
	exit;
}
$result = $DB->fetch();

$isCorrect = 0;
if($_POST["getOptType"] != "R") {
	if( strtolower(trim($result[0])) == strtolower(trim($_POST["smtOption"])) )
		$isCorrect = 1;
} else {
	$corAr = explode(",", $result[0]);
	if(count($_POST["smtOption"])) {
		if(!count(array_diff($corAr, $_POST["smtOption"]))) {
			$isCorrect = 1;
		} 
	}
}

// 제출 정보파일을 읽어온다.
// 정보파일 경신을 위한 준비
// 다음 ItemID 선택
// $nextItem의 값이 비어 있으면 다 푼 것임.
$dataPath = DATA_PATH . "exam/" . $_POST["examID"] . "/" . $_SESSION["UID"] . "/";
$myDataFile = $dataPath . $_SESSION["ID"];
$stdAnswer = file($myDataFile);
reset($stdAnswer);
$value = trim(current($stdAnswer));
$writeString = "";
$nextItem = "";
while($value) {
	$srchID = sprintf("%08d", intval($_POST["myItem"]));
	$curID = substr($value, 0, 8);
	$curSol = substr($value, -1, 1);
	if($srchID == $curID) {
		$writeString .= $curID . "1" . "\n";
	} else {
		$writeString .= $value . "\n";
		if($curSol == "0" AND empty($nextItem)) {
			$nextItem = intval($curID);
		}
	}	
	$value = trim(next($stdAnswer));
}


// DB 확인 - 이미 제출된 문제인지
// $sql = "SELECT count(*) FROM ExamSubmit WHERE examid =  " . $_POST["examID"] . " AND userid =  '" . $_SESSION["ID"] . "' AND itemid =  " . $_POST["myItem"] . ")";


// DB에 저장
$sql = "INSERT INTO ExamSubmit VALUES (nextval('examsubmit_myid_seq'::regclass), " . $_POST["examID"] . ", '" . $_SESSION["ID"] . "', " . $_POST["myItem"] . ", '" . $isCorrect . "', CURRENT_TIMESTAMP)";
if(!$DB->query($sql)) {
	$fnc->alertBack("제출내역을 DB에 기록할 수 없습니다.");
	exit;
}

// 변경된 값 File저장
$fp = fopen($myDataFile, "w");
fwrite($fp, $writeString);
fclose($fp);

// 시험 종료
if(empty($nextItem)) {
	$sql = "UPDATE ExamStudent SET stdStatus = 'Y' WHERE examID = " . $_POST["examID"] . " AND studentID = '" . $_SESSION["ID"] . "'";
	if(!$DB->query($sql)) {
		$fnc->alertBack("종료정보를 DB에 기록할 수 없습니다.");
		exit;
	}
?>
<SCRIPT LANGUAGE="JavaScript">
	alert('시험이 종료되었습니다.');
	parent.location.href='<?=URL_ROOT?>takeExam/endExam.php';
</SCRIPT>
<?
}
?>
<SCRIPT LANGUAGE="JavaScript">
	parent.location.href='<?=URL_ROOT?>takeExam/takeExam.php?examID=<?=$_POST["examID"]?>&myItem=<?=$nextItem?>&isFirst=N';
</SCRIPT>
<?
ob_end_flush();
?>
