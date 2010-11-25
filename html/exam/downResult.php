<?php
/* =============================================================================
File : exam/downResult.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	접근가능 : A, F
	결과 File 받기 
	- $_GET["examID"] 로부터 결과를 가져온다.
	- 전체 관리자는 전체 제출 현황을, 
	  학교별관리자는 학교별 제출현황을 보여준다.
	- 표시형식 : 
	0000000000,1,2,3,4,5,6,7,8,9,10,11,12
	사용자ID,1,0,1,1....
============================================================================= */

$noDBI = 3;
$usePGNav = "Y";
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
//출제 문항 가져오기
$sql2 = "SELECT a.myID FROM (ItemTable as a JOIN ExamItem as b ON (a.myID = b.itemID)) JOIN ItemGInfoTable as c ON (a.myID = c.itemID) WHERE b.examID = " . $_GET["examID"] . " ORDER BY c.groupID, c.groupOrder";
	
if(!$DB[0]->query($sql2)) {
	echo $sql2;
//	$fnc->alertBack("학생 제출정보를 가져올 수 없습니다.");
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
// Paging을 위한 전체 갯수 출력
// 관리자별 출력결과를 다르게

// 학생목록 출력 
// 관리자별 출력결과를 다르게
// 학교, 학번, 성명(아이디), 맞춘문제수 / 전체문제수, 점수
if($_SESSION["Level"] == "A") {
	$sql = "SELECT studentID FROM ExamStudent  WHERE examID = " . $_GET["examID"] ;
} else {
	$sql = "SELECT studentID FROM ExamStudent as a  JOIN UserInfo as b ON (a.studentID = b.myID) WHERE examID = " . $_GET["examID"] . " AND b.univID = '" . $_SESSION["UID"] . "'" ;
}

if(!$DB[0]->query($sql)) {
	$fnc->alertBack("응시생 리스트를 가져올 수 없습니다.");
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
			$fnc->alertBack("응시생 리스트를 가져올 수 없습니다.");
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
	$fnc->alertBack("시험명을 가져올 수 없습니다.");
	exit;
}
$result = $DB[0]->getResult(0, 0);
$downFileName = $result . "결과.txt";

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
