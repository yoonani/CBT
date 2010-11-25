<?php
/* =============================================================================
File : itemAdmin/procGrpItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	Group내 문항 추가
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}
// print_r($_POST);

// 문항의 종류에 따라 정답 문자열을 다르게 만든다.
// 선다형과 단답형의 경우는 원래 문자열로
// 다중선택의 경우는 (,)를 glue로 하여 문자열을 만들고
if($_POST["getFormat"] != "R") {
	// 다중선택형이 아니면
	$myCorrect = trim($_POST["myCorrect"]);
} else if($_POST["getItemInputType"] == 'N' AND $_POST["getFormat"] == "R") {
	// 다중선택형이면
	$myCorrect = "";
	$corNum = count($_POST["myCorrect"]);
	for($i=0; $i < $corNum; $i++) {
		if($i < $corNum - 1) {
			$myCorrect .= trim($_POST["myCorrect"][$i]) . ",";
		} else {
			$myCorrect .= trim($_POST["myCorrect"][$i]);
		}
	}
} else {
	// 외부자료 문제이고 다중선택형이 아니면
	$myCorrect = trim($_POST["myCorrect"]);
}

// Transaction 시작
$fnc->beginTrans($DB);

if(trim($_POST["isIndep"] == "N")) {
	$myGrpID = $_POST["myGrpID"];
} else {
	$sql = "SELECT nextval('itemgrouptable_myid_seq')"; 
	if(!$DB->query($sql)) {
		$fnc->alertback("새로운 Group정보를 가져올 수 없습니다.");
		exit;
	}
	$myGrpID = $DB->getResult(0, 0);
	if(empty($myGrpID)) {
		$myGrpID = 1;
	} 
	$sql = "INSERT INTO ItemGroupTable VALUES (" . $myGrpID . ", '" . trim($_POST["getContents"]) . "', 'Y', '', '')"; 
	if(!$DB->query($sql)) {
		$fnc->alertback("새로운 Group정보를 입력할 수 없습니다.");
		exit;
	}
	$sql = "INSERT INTO ExamGroup VALUES (nextval('examgroup_myid_seq'), " . $_POST["examID"] . ", " . $myGrpID . ")";
	if(!$DB->query($sql)) {
		$fnc->alertback("새로운 시험 Group정보를 입력할 수 없습니다.");
		exit;
	}
}


// 시험내의 순서를 정하기 위해 문항순서 계산
// 자신의 Group ID 이전에 출제된 문항의 출제수 가져온후...
// 다음에 계산하는 $myGrpNum과 합치면 된다.
$sql = "SELECT count(*) FROM ExamItem WHERE examID = " . $_POST["examID"] . " AND groupID < " . $myGrpID;
if(!$DB->query($sql)) {
	$fnc->alertBack("기존 출제문항수를 가져올 수 없습니다.");
	exit;
}
$myExamOrder = $DB->getResult(0, 0);


if($_POST["isIndep"] == "N") {
	// Group내의 순서를 정하기 위해 기존 Group내의 문항수를 가져온다.
	$sql = "SELECT count(*) FROM ItemGInfoTable WHERE groupID = " . $myGrpID;
	if(!$DB->query($sql)) {
		$fnc->alertBack("기존 Group 정보를 가져올 수 없습니다.");
		exit;
	}
	$myGrpNum = $DB->getResult(0, 0) + 1;
} else {
	$myGrpNum = 1;
}

// 현재 문항이전까지 출제된 문항
$myExamOrder += $myGrpNum;


// 입력될 문항의 ID를 가져온다.
$sql = "SELECT nextval('itemtable_myid_seq')";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("문항 ID를 생성할 수 없습니다.");
	exit;
}
$itemID = $DB->getResult(0, 0);
//echo $itemID;



// ItemTable에 정보를 저장한다.
// 단독문항은 Group Info에 해당 문항이 있다.
if($_POST["isIndep"] == "N") {
	$itemContents = trim($_POST["getContents"]);
} else {
	$itemContents = "";
}
$sql = "INSERT INTO ItemTable VALUES (" . $itemID . ", '" . $itemContents . "', '" . $myCorrect . "', " .$_POST["myScore"] . ", '" . trim($_POST["getItemInputType"]) . "')";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("기본문항 정보를 입력할 수 없습니다.");
	exit;
}


// Form을 통해 저장하면
if(trim($_POST["getItemInputType"]) == "N") {
	// OptionTable에 출제된 Option을 저장한다.
	// Option들이 전달된 값
	// getOptTF1 - 10
	// getOptIMG1 - 10
	for($i=0; $i < $_POST["getOptNo"]; $i++) {
		$myNum = $i + 1;
		$myTF = "getOptTF" . $myNum;
		$myIMG = "getOptIMG" . $myNum;

		//
		// Bio Add 06.07.16 10:23 For 보기 파일 저장 
		//
		// 파일 업로드
		$myMicrotime = substr(microtime(),strrpos(microtime(),".")+1); // microtime 소숫점 분리
		$sFile = str_replace(" ","",$myMicrotime); // microtime 공백제거
		$sFileName = "op".trim($sFile).trim(substr($_FILES[$myIMG]["name"], strrpos($_FILES[$myIMG]["name"],"."))); // 파일명 변경
		$dir = IMG_PATH."testimages/".trim($_POST["examID"])."/"; // IMG가 올라갈 경로
		$dest = $dir."/".$sFileName;
		if(is_uploaded_file(trim($_FILES[$myIMG]["tmp_name"]))) {
			if(!copy(trim($_FILES[$myIMG]["tmp_name"]),$dest)) {
				$fnc->alert("이미지 업로드에 실패하였습니다.");
	        	}
	        	if(!unlink(trim($_FILES[$myIMG]["tmp_name"]))) {
				$fnc->alert("이미지 업로드에 실패하였습니다.");
	        	}
		}
		//
		// Bio's Add End
		//

		//
		// This is Original SQL
		//
		//$sql = "INSERT INTO OptionTable VALUES ( nextval('optiontable_myid_seq'), " . $itemID . ", " . $myNum . ", '" . trim($_POST[$myTF]) . "', '" . trim($_FILES[$myIMG]["name"]) . "')";

		//
		// This is Bio's Modify SQL
		// Modify Part : trim($_FILES[$myIMG]["name"]) -> $sFileName
		// 06.07.16 10:24
		//
		$sql = "INSERT INTO OptionTable VALUES ( nextval('optiontable_myid_seq'), " . $itemID . ", " . $myNum . ", '" . trim($_POST[$myTF]) . "', '" . $sFileName . "')";
		if(!$DB->query($sql)) {
			$fnc->rollbackTrans($DB);
			$fnc->alertBack("보기 " . $myNum . "번을 입력할 수 없습니다.");
			exit;
		}
	}
} else {
// 외부파일로 저장되면
	$sql = "INSERT INTO ObjectTable VALUES (nextval('objecttable_myid_seq'), " . $itemID . ", '" . $_FILES["getFile"]["name"]. "')";
	if(!$DB->query($sql)) {
		$fnc->rollbackTrans($DB);
		$fnc->alertBack("외부파일을 저장할 수 없습니다.");
		exit;
	}
}
	
// ItemAInfoTable에 정보를 저장한다.
$sql = "INSERT INTO ItemAInfoTable VALUES (nextval('itemainfotable_myid_seq'), " . $itemID . ", '" . $_POST["getFormat"] . "', '" . $_POST["getCategory"] . "', '" . $_POST["getLevel"] . "', '" . $_POST["getSubject"] . "', '" . trim($_POST["getMeSH"]) . "', '" . $_POST["getCase"] . "', '" . $_POST["getOptType"] . "')";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("추가문항 정보를 입력할 수 없습니다.");
	exit;
}

// ItemGInfoTable에 값을 저장한다.
// 어느 Group의 문항인지...
$sql = "INSERT INTO ItemGInfoTable VALUES (" . $itemID . ", "  . $myGrpID . ", " . $myGrpNum . ")";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
//	echo $sql;
	$fnc->alertBack("Group문항 정보를 입력할 수 없습니다.");
	exit;
}

// ExamItem 에 값을 저장한다.
$sql =  "INSERT INTO ExamItem VALUES (" . $_POST["examID"] . ", " . $itemID . ", " . $myGrpID . ", " . $myExamOrder . ")";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("해당 문항을 시험에 출제할 수 없습니다.");
	exit;
}


// ExamAdmin의 myItemNo(출제 문항수) 1증가
$sql = "UPDATE ExamAdmin SET myItemNo = myItemNo + 1 WHERE myID = " . $_POST["examID"];
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("출제문항수를 증가할 수 없습니다.");
	exit;
}

// 자신의 GroupID보다 큰것들의 Order를  1씩증가
$sql = "UPDATE ExamItem SET examOrder = examOrder + 1 WHERE examID = " . $_POST["examID"] . " AND groupID > " . $myGrpID;
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("기존 출제문항수를 가져올 수 없습니다.");
	exit;
}

$fnc->commitTrans($DB);

if($_POST["isIndep"] == "N") {
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>itemAdmin/viewGrpItem.php?examID=<?=$_POST["examID"]?>&myPg=<?=$_POST["myPg"]?>&myGrpID=<?=$myGrpID?>&isIndep=<?=$_POST["isIndep"]?>">
<?
} else {
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?=URL_ROOT?>itemAdmin/itemIndex.php?examID=<?=$_POST["examID"]?>&myPg=<?=$_POST["myPg"]?>">
<?
}
require_once (MY_INCLUDE . "closing.php");
?>

