<?php
/* =============================================================================
File : eachUniv/chExamStatus.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A, F
	������ ����ڿ� ���� ����/������ ��ȯ
	- $_POST
	examID : ���� ���̵�
	myStatus : S ���� -> ������, U ������ -> ����
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
print_r($_POST);
if($_POST["myStatus"] == "U") {
// ������ -> ����
	reset($_POST["myCB2"]);
	$value = current($_POST["myCB2"]);
	$i=0;
	while($value) {
		// $iid = ������ ������ �߰��� ����� ID
		$iid = trim($value);
		$sql = "INSERT INTO ExamStudent VALUES (nextval('examstudent_myid_seq'), " . $_POST["examID"] . ", '" . $iid . "')";
		if(!$DB->query($sql)) {
			$fnc->alertBack($iid . "����ڸ� �Է��� �� �����ϴ�.");
			exit;
		}
		$i++;
		$value = next($_POST["myCB2"]);
	}
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("�� <?=$i?>���� ����ڸ� ���迡 ����Ͽ����ϴ�.");
</SCRIPT>



<?
} else {
// ���� -> ������
	reset($_POST["myCB"]);
	$value = current($_POST["myCB"]);
	$i=0;
	while($value) {
		// $iid = ������ ������ �߰��� ����� ID
		$iid = trim($value);
		$sql = "DELETE FROM  ExamStudent WHERE examID = " . $_POST["examID"] . " AND studentID =  '" . $iid . "'";
		echo $sql;
		if(!$DB->query($sql)) {
			$fnc->alertBack($iid . "����ڸ� ���迡�� ������ �� �����ϴ�.");
			exit;
		}
		$i++;
		$value = next($_POST["myCB"]);
	}
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("�� <?=$i?>���� ����ڸ� ���迡�� �����Ͽ����ϴ�.");
</SCRIPT>


<?
}
?>


<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>eachUniv/regStd2Exam.php?examID=<?=$_POST["examID"]?>">
<?
require_once (MY_INCLUDE . "closing.php");
?>
