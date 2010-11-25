<?php
/* =============================================================================
File : takeExam/index.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A, B
	각 시험의 첫 페이지로 각종 설정을 초기화하고 takeExam.php로 이동
	$_GET["examID"]를 받는다
============================================================================= */
$noDBI = 2;
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}

$isFirst = "N";
$myItem = false;

$fnc->alertDiffWin("examWin", "열 수 없는 창입니다.");

$dataPath = DATA_PATH . "exam/" . $_GET["examID"] . "/" ;
if(!file_exists($dataPath)) {
	mkdir($dataPath);
}
$dataPath .= $_SESSION["UID"] . "/";
if(!file_exists($dataPath)) {
	mkdir($dataPath);
}

// 학생의 경우 제출 정보 초기화
if($_SESSION["Level"] == "B") {
	$sql = "SELECT stdStatus FROM ExamStudent WHERE examID = " . $_GET["examID"] . " AND studentID = '" . $_SESSION["ID"] . "'";
	if(!$DB[0]->query($sql)) {
		$fnc->alertBack("종료여부를 확인할 수 없습니다.");
		exit;
	}
	$isOver = $DB[0]->getResult(0, 0);
	if($isOver == "Y") {
?>
<SCRIPT LANGUAGE="JavaScript">
	alert('이미 제출된 시험입니다.');
	opener.location.href='<?=URL_ROOT?>exam/examList.php';
	window.close();
</SCRIPT>
<?
		exit;
	}

	$myDataFile = $dataPath . $_SESSION["ID"];

	// 출제정보 파일이 존재하지 않으면 생성한다.
	// 문항 File Format
	// 문항ID(8자리),(1|0) 풀면 1 안풀면 0
	// ID 선택은 Group ID의 Random Selection을 하고 해당 Group내의 Item의 순서로 나온다.
	$fileStr = "";
	if(!file_exists($myDataFile)) {
		// Group의 Random Selection
		$sql = "SELECT a.groupID FROM ExamGroup as a JOIN ItemGroupTable as b ON (a.groupID = b.myID)  WHERE a.examID = " . $_GET["examID"] . " ORDER BY random()";
		if(!$DB[0]->query($sql)) {
			$fnc->alertBack("제출 파일 생성 오류 - Group 정보를 가져올 수 없습니다.");
			exit;
		}
		while($result = $DB[0]->fetch()) {
			// Group이 포함하고 있는 문항 선택
			$sql2 = "SELECT a.itemID, b.groupOrder FROM ExamItem as a JOIN ItemGInfoTable as b ON (a.itemID = b.itemID) WHERE a.groupID = " . $result[0] . " AND a.examID = " . $_GET["examID"] . " ORDER BY b.groupOrder";
			if(!$DB[1]->query($sql2)) {
//				echo $sql2;
				$fnc->alertBack("제출 파일 생성 오류 - Item 정보를 가져올 수 없습니다.");
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
	} // 출제정보 파일 생성 끝

	// 처음 접속이면 첫번째 ID를
	// 다시 접속하는 것이면 처음으로 안 푼 문제의 ID를
	// 변수 $myItem에 넣는다.
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
		$fnc->alertBack("다음 문항을 가져올 수 없습니다.");
		exit;
	}
} // 학생의 경우 끝

?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>takeExam/takeExam.php?examID=<?=$_GET["examID"]?>&myItem=<?=$myItem?>&isFirst=<?=$isFirst?>">
<?
ob_end_flush();
?>
