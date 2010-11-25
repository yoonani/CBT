<?php
/* =============================================================================
File : takeExam/index.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A, B
	�� ������ ù �������� ���� ������ �ʱ�ȭ�ϰ� takeExam.php�� �̵�
	$_GET["examID"]�� �޴´�
============================================================================= */
$noDBI = 2;
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}

$isFirst = "N";
$myItem = false;

$fnc->alertDiffWin("examWin", "�� �� ���� â�Դϴ�.");

$dataPath = DATA_PATH . "exam/" . $_GET["examID"] . "/" ;
if(!file_exists($dataPath)) {
	mkdir($dataPath);
}
$dataPath .= $_SESSION["UID"] . "/";
if(!file_exists($dataPath)) {
	mkdir($dataPath);
}

// �л��� ��� ���� ���� �ʱ�ȭ
if($_SESSION["Level"] == "B") {
	$sql = "SELECT stdStatus FROM ExamStudent WHERE examID = " . $_GET["examID"] . " AND studentID = '" . $_SESSION["ID"] . "'";
	if(!$DB[0]->query($sql)) {
		$fnc->alertBack("���Ῡ�θ� Ȯ���� �� �����ϴ�.");
		exit;
	}
	$isOver = $DB[0]->getResult(0, 0);
	if($isOver == "Y") {
?>
<SCRIPT LANGUAGE="JavaScript">
	alert('�̹� ����� �����Դϴ�.');
	opener.location.href='<?=URL_ROOT?>exam/examList.php';
	window.close();
</SCRIPT>
<?
		exit;
	}

	$myDataFile = $dataPath . $_SESSION["ID"];

	// �������� ������ �������� ������ �����Ѵ�.
	// ���� File Format
	// ����ID(8�ڸ�),(1|0) Ǯ�� 1 ��Ǯ�� 0
	// ID ������ Group ID�� Random Selection�� �ϰ� �ش� Group���� Item�� ������ ���´�.
	$fileStr = "";
	if(!file_exists($myDataFile)) {
		// Group�� Random Selection
		$sql = "SELECT a.groupID FROM ExamGroup as a JOIN ItemGroupTable as b ON (a.groupID = b.myID)  WHERE a.examID = " . $_GET["examID"] . " ORDER BY random()";
		if(!$DB[0]->query($sql)) {
			$fnc->alertBack("���� ���� ���� ���� - Group ������ ������ �� �����ϴ�.");
			exit;
		}
		while($result = $DB[0]->fetch()) {
			// Group�� �����ϰ� �ִ� ���� ����
			$sql2 = "SELECT a.itemID, b.groupOrder FROM ExamItem as a JOIN ItemGInfoTable as b ON (a.itemID = b.itemID) WHERE a.groupID = " . $result[0] . " AND a.examID = " . $_GET["examID"] . " ORDER BY b.groupOrder";
			if(!$DB[1]->query($sql2)) {
//				echo $sql2;
				$fnc->alertBack("���� ���� ���� ���� - Item ������ ������ �� �����ϴ�.");
				exit;
			}
			while($result2 = $DB[1]->fetch()) {
				$fileStr .= sprintf("%08d0\n", $result2[0]);
			}
		}
		$isFirst = "Y";
		$fp = fopen($myDataFile, "w");
		fwrite($fp, $fileStr);
		fclose($fp);
	} // �������� ���� ���� ��

	// ó�� �����̸� ù��° ID��
	// �ٽ� �����ϴ� ���̸� ó������ �� Ǭ ������ ID��
	// ���� $myItem�� �ִ´�.
	if($isFirst == "Y") {
		$myItem = intval(substr($fileStr, 0, 8));
	} else {
		$tmp = file($myDataFile);
		$isNotSolve = false;
		reset($tmp);
		$value = current($tmp);
		while($value) {
			$cLine = trim($value);
			$tmpID = substr(trim($value), 0, 8);
			$chkIt = substr(trim($value), 8, 1);
			if($chkIt == "0")  {
				$myItem = intval($tmpID);
				break;
			}
			$value = next($tmp);
		}
	}

	if(!$myItem) {
		$fnc->alertBack("���� ������ ������ �� �����ϴ�.");
		exit;
	}
} // �л��� ��� ��

?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>takeExam/takeExam.php?examID=<?=$_GET["examID"]?>&myItem=<?=$myItem?>&isFirst=<?=$isFirst?>">
<?
ob_end_flush();
?>
