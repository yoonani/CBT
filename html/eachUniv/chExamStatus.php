<?php
/* =============================================================================
File : eachUniv/chExamStatus.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A, F
	선택한 사용자에 대한 응시/미응시 변환
	- $_POST
	examID : 시험 아이디
	myStatus : S 응시 -> 미응시, U 미응시 -> 응시
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
print_r($_POST);
if($_POST["myStatus"] == "U") {
// 미응시 -> 응시
	reset($_POST["myCB2"]);
	$value = current($_POST["myCB2"]);
	$i=0;
	while($value) {
		// $iid = 공백을 제거한 추가할 사용자 ID
		$iid = trim($value);
		$sql = "INSERT INTO ExamStudent VALUES (nextval('examstudent_myid_seq'), " . $_POST["examID"] . ", '" . $iid . "')";
		if(!$DB->query($sql)) {
			$fnc->alertBack($iid . "사용자를 입력할 수 없습니다.");
			exit;
		}
		$i++;
		$value = next($_POST["myCB2"]);
	}
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("총 <?=$i?>명의 사용자를 시험에 등록하였습니다.");
</SCRIPT>



<?
} else {
// 응시 -> 미응시
	reset($_POST["myCB"]);
	$value = current($_POST["myCB"]);
	$i=0;
	while($value) {
		// $iid = 공백을 제거한 추가할 사용자 ID
		$iid = trim($value);
		$sql = "DELETE FROM  ExamStudent WHERE examID = " . $_POST["examID"] . " AND studentID =  '" . $iid . "'";
		echo $sql;
		if(!$DB->query($sql)) {
			$fnc->alertBack($iid . "사용자를 시험에서 삭제할 수 없습니다.");
			exit;
		}
		$i++;
		$value = next($_POST["myCB"]);
	}
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("총 <?=$i?>명의 사용자를 시험에서 삭제하였습니다.");
</SCRIPT>


<?
}
?>


<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>eachUniv/regStd2Exam.php?examID=<?=$_POST["examID"]?>">
<?
require_once (MY_INCLUDE . "closing.php");
?>
