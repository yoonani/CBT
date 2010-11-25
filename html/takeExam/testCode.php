<?php
/* =============================================================================
File : takeExam/testCode.php
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


// DB에 저장
$sql = "INSERT INTO ExamSubmit2 VALUES (100, 'yoonani2', 614, 1 , CURRENT_TIMESTAMP)";
if(!$DB->query($sql)) {
	echo $sql;
	echo $DB->error();
//	$fnc->alertBack("제출내역을 DB에 기록할 수 없습니다.");
	exit;
}

ob_end_flush();
?>
