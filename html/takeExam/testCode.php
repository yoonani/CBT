<?php
/* =============================================================================
File : takeExam/testCode.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	���ٰ��� : A, B, F
	���� ��� ����
	- $_POST["smtOption"] | $_POST["smtOption"][]
	- $_POST["examID"]
	- $_POST["myItem"]
	- $_POST["getOptType"]
	- $_POST["getOptNo"]
============================================================================= */
require_once("../../include/conf.php");


// DB�� ����
$sql = "INSERT INTO ExamSubmit2 VALUES (100, 'yoonani2', 614, 1 , CURRENT_TIMESTAMP)";
if(!$DB->query($sql)) {
	echo $sql;
	echo $DB->error();
//	$fnc->alertBack("���⳻���� DB�� ����� �� �����ϴ�.");
	exit;
}

ob_end_flush();
?>
