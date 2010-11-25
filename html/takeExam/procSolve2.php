<?php
/* =============================================================================
File : takeExam/procSolve.php
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
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
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
	$fnc->alertBack("������ ������ �� �����ϴ�.");
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

// ���� ���������� �о�´�.
// �������� ����� ���� �غ�
// ���� ItemID ����
// $nextItem�� ���� ��� ������ �� Ǭ ����.
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


// DB Ȯ�� - �̹� ����� ��������
// $sql = "SELECT count(*) FROM ExamSubmit WHERE examid =  " . $_POST["examID"] . " AND userid =  '" . $_SESSION["ID"] . "' AND itemid =  " . $_POST["myItem"] . ")";


// DB�� ����
$sql = "INSERT INTO ExamSubmit VALUES (nextval('examsubmit_myid_seq'::regclass), " . $_POST["examID"] . ", '" . $_SESSION["ID"] . "', " . $_POST["myItem"] . ", '" . $isCorrect . "', CURRENT_TIMESTAMP)";
if(!$DB->query($sql)) {
	$fnc->alertBack("���⳻���� DB�� ����� �� �����ϴ�.");
	exit;
}

// ����� �� File����
$fp = fopen($myDataFile, "w");
fwrite($fp, $writeString);
fclose($fp);

// ���� ����
if(empty($nextItem)) {
	$sql = "UPDATE ExamStudent SET stdStatus = 'Y' WHERE examID = " . $_POST["examID"] . " AND studentID = '" . $_SESSION["ID"] . "'";
	if(!$DB->query($sql)) {
		$fnc->alertBack("���������� DB�� ����� �� �����ϴ�.");
		exit;
	}
?>
<SCRIPT LANGUAGE="JavaScript">
	alert('������ ����Ǿ����ϴ�.');
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
