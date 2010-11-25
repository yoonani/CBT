<?
/* =============================================================================
File : viewResult/downResultFile.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 9
================================================================================
Desc.
	전체관리자와 학교별 관리자가 사용가능
	각 문항 및 제출 학생별로 맞으면 1, 틀리면 0으로 표시되는 파일을 다운로드받는다.
	전체관리자의 경우 학교코드(2자리),학생ID(10자리)이후 문항별 정오
	학교관리자의 경우 학생의 학번(15자리), 이후 문항별 정오
	- 전달변수
	: $_GET["examID"] - 시험 ID
============================================================================= */
$noDBI = 2;
require_once("../../include/confnoBuf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}

$sql = "SELECT itemid FROM examitem WHERE examid = " . $_GET["examID"] . " ORDER BY examorder";
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("문항정보를 가져올 수 없습니다.");
	exit;
}
$examItems = array();
while($tmp =$DB[0]->fetch()) {
	array_push($examItems, $tmp[0]);
}

switch($_SESSION["Level"]) {
	case "A" :
		$headStr = "000000000000";
		$sql2 = "SELECT B.univid, A.studentid FROM examstudent as A, userinfo as B WHERE A.studentid = B.myid AND a.examid = " . $_GET["examID"] . " ORDER BY B.univsno";
		break;
	case "F" :
		$headStr = "000000000000000";
		$sql2 = "SELECT B.univsno, A.studentid FROM examstudent as A, userinfo as B WHERE A.studentid = B.myid AND a.examid = " . $_GET["examID"] . " ORDER BY B.univsno";
		break;
}
if(!$DB[0]->query($sql2)) {
	$fnc->alertBack("제출정보를 가져올 수 없습니다.");
	exit;
}

for($i=1; $i <= count($examItems); $i++) {
	$headStr .= ($i % 10);
}
$headStr .= "\n";

$prtItemStr = "";

while($ans = $DB[0]->fetch()) {
	switch($_SESSION["Level"]) {
		case "A" :
			$cHead = $ans[0] . sprintf("%010s", trim($ans[1]));
			break;
		case "F" :
			$cHead = sprintf("%015s", trim($ans[0]));
			break;
	}
	$findStd = trim($ans[1]);

	$prtItemStr .= $cHead;
	for($i = 0; $i < count($examItems); $i++) {
		$sql3 = "SELECT iscorrect FROM examsubmit WHERE examid = " . $_GET["examID"] . " AND itemid = " . $examItems[$i] . " AND userid = '" . trim($findStd). "'";
		if(!$DB[1]->query($sql3)) {
			$fnc->alertBack("학생의 제출정보를 가져올 수 없습니다.");
			exit;
		}
		$prtItem = "0";
		if($DB[1]->getResult(0,0) == "1") {
			$prtItem = "1";
		}
		$prtItemStr .= $prtItem;
	}
	$prtItemStr .= "\n";
//	$prtItemStr .= "<br>";
	$downStr = $headStr . $prtItemStr;
}
header("Cache-control: private"); 
$sql = "SELECT mytitle FROM examadmin WHERE myid = " . $_GET["examID"];
if(!$DB[0]->query($sql)) {
	$fnc->alertBack("시험정보를 가져올 수 없습니다.");
	exit;
}
 
if (eregi("(MSIE 5.5|MSIE 6.0)", $_SERVER["HTTP_USER_AGENT"])) {
	Header("Content-type:application/octet-stream"); 
	Header("Content-Length:".strlen($downStr));
	Header("Content-Disposition:attachment; filename=".$DB[0]->getResult(0,0) . "결과.txt");
//	Header("Content-Disposition:attachment;filename=".$_GET["examID"]);
	Header("Content-Transfer-Encoding:binary"); 
	Header("Pragma:no-cache"); 
	Header("Expires:0"); 
} else {
	Header("Content-type:file/unknown"); 
	Header("Content-Length:".strlen($downStr));
	Header("Content-Disposition:attachment; filename=".$DB[0]->getResult(0,0) . "결과.txt");
	Header("Content-Description:PHP Generated Data"); 
	Header("Pragma: no-cache"); 
	Header("Expires: 0"); 
}

echo $downStr;
?>
