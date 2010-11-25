<?php
/* =============================================================================
File : exam/downResult.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	���ٰ��� : A, F
	��� File �ޱ� 
	- $_GET["examID"] �κ��� ����� �����´�.
	- ��ü �����ڴ� ��ü ���� ��Ȳ��, 
	  �б��������ڴ� �б��� ������Ȳ�� �����ش�.
	- ǥ������ : 
	0000000000,1,2,3,4,5,6,7,8,9,10,11,12
	�����ID,1,0,1,1....
============================================================================= */

$noDBI = 3;
$usePGNav = "Y";
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
//���� ���� ��������
$sql2 = "SELECT a.myID FROM (ItemTable as a JOIN ExamItem as b ON (a.myID = b.itemID)) JOIN ItemGInfoTable as c ON (a.myID = c.itemID) WHERE b.examID = " . $_GET["examID"] . " ORDER BY c.groupID, c.groupOrder";
	
if(!$DB[0]->query($sql2)) {
	echo $sql2;
//	$fnc->alertBack("�л� ���������� ������ �� �����ϴ�.");
	exit;
}
$totalItems = $DB[0]->noRows();
$itemArray = array();
while($result = $DB[0]->fetch()) {
	array_push($itemArray, $result[0]);
}

$headerString = "0000000000|";
for($i=0; $i < $totalItems; $i++) {
	$idx = ($i + 1) % 10;
	if($i != $totalItems - 1) {
		$headerString .=  $idx . "|";
	} else {
		$headerString .=  $idx . "\n";
	}
}
// Paging�� ���� ��ü ���� ���
// �����ں� ��°���� �ٸ���

// �л���� ��� 
// �����ں� ��°���� �ٸ���
// �б�, �й�, ����(���̵�), ���Ṯ���� / ��ü������, ����
if($_SESSION["Level"] == "A") {
	$sql = "SELECT studentID FROM ExamStudent  WHERE examID = " . $_GET["examID"] ;
} else {
	$sql = "SELECT studentID FROM ExamStudent as a  JOIN UserInfo as b ON (a.studentID = b.myID) WHERE examID = " . $_GET["examID"] . " AND b.univID = '" . $_SESSION["UID"] . "'" ;
}

if(!$DB[0]->query($sql)) {
	$fnc->alertBack("���û� ����Ʈ�� ������ �� �����ϴ�.");
	exit;
}
$bodyString = "";
while($result = $DB[0]->fetch()) {
	$bodyString .= sprintf("%'010s", trim($result[0])) . "|";
	$i = 0;
	reset($itemArray);
	$value = current($itemArray);
	while($value) {
		$sql = "SELECT isCorrect FROM ExamSubmit WHERE examID = " . $_GET["examID"] . " AND userID = '" . trim($result[0]). "' AND itemID = " . $value;
		if(!$DB[1]->query($sql)) {
			$fnc->alertBack("���û� ����Ʈ�� ������ �� �����ϴ�.");
			exit;
		}
//		echo $sql . "\n";
		$stdSmt = $DB[1]->getResult(0, 0);
		
		if(empty($stdSmt)) {
			$isCor = "0";
		} else {
			$isCor = $stdSmt;
		}

		if($i != $totalItems - 1) {
			$bodyString .=  $isCor . "|";
		} else {
			$bodyString .=  $isCor . "\n";
		}
		$i++;
		$value = next($itemArray);
	}
}

$sql = "SELECT myTitle FROM ExamAdmin WHERE myID = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("������� ������ �� �����ϴ�.");
	exit;
}
$result = $DB[0]->getResult(0, 0);
$downFileName = $result . "���.txt";

$downContents = $headerString . $bodyString;
//echo nl2br($downContents);

header("Content-type: file/unknown"); 
Header("Content-Disposition: attachment; filename=".($downFileName)); 
//header("Content-Transfer-Encoding: binary"); 
Header("Content-Length: ".(string)(strlen($downContents))); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

echo $downContents;


ob_end_flush();
?>
