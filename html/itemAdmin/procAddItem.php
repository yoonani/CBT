<?php
/* =============================================================================
File : itemAdmin/procGrpItem.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	Group�� ���� �߰�
============================================================================= */
require_once("../../include/conf.php");
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
// print_r($_POST);

// ������ ������ ���� ���� ���ڿ��� �ٸ��� �����.
// �������� �ܴ����� ���� ���� ���ڿ���
// ���߼����� ���� (,)�� glue�� �Ͽ� ���ڿ��� �����
if($_POST["getFormat"] != "R") {
	// ���߼������� �ƴϸ�
	$myCorrect = trim($_POST["myCorrect"]);
} else if($_POST["getItemInputType"] == 'N' AND $_POST["getFormat"] == "R") {
	// ���߼������̸�
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
	// �ܺ��ڷ� �����̰� ���߼������� �ƴϸ�
	$myCorrect = trim($_POST["myCorrect"]);
}

// Transaction ����
$fnc->beginTrans($DB);

if(trim($_POST["isIndep"] == "N")) {
	$myGrpID = $_POST["myGrpID"];
} else {
	$sql = "SELECT nextval('itemgrouptable_myid_seq')"; 
	if(!$DB->query($sql)) {
		$fnc->alertback("���ο� Group������ ������ �� �����ϴ�.");
		exit;
	}
	$myGrpID = $DB->getResult(0, 0);
	if(empty($myGrpID)) {
		$myGrpID = 1;
	} 
	$sql = "INSERT INTO ItemGroupTable VALUES (" . $myGrpID . ", '" . trim($_POST["getContents"]) . "', 'Y', '', '')"; 
	if(!$DB->query($sql)) {
		$fnc->alertback("���ο� Group������ �Է��� �� �����ϴ�.");
		exit;
	}
	$sql = "INSERT INTO ExamGroup VALUES (nextval('examgroup_myid_seq'), " . $_POST["examID"] . ", " . $myGrpID . ")";
	if(!$DB->query($sql)) {
		$fnc->alertback("���ο� ���� Group������ �Է��� �� �����ϴ�.");
		exit;
	}
}


// ���賻�� ������ ���ϱ� ���� ���׼��� ���
// �ڽ��� Group ID ������ ������ ������ ������ ��������...
// ������ ����ϴ� $myGrpNum�� ��ġ�� �ȴ�.
$sql = "SELECT count(*) FROM ExamItem WHERE examID = " . $_POST["examID"] . " AND groupID < " . $myGrpID;
if(!$DB->query($sql)) {
	$fnc->alertBack("���� �������׼��� ������ �� �����ϴ�.");
	exit;
}
$myExamOrder = $DB->getResult(0, 0);


if($_POST["isIndep"] == "N") {
	// Group���� ������ ���ϱ� ���� ���� Group���� ���׼��� �����´�.
	$sql = "SELECT count(*) FROM ItemGInfoTable WHERE groupID = " . $myGrpID;
	if(!$DB->query($sql)) {
		$fnc->alertBack("���� Group ������ ������ �� �����ϴ�.");
		exit;
	}
	$myGrpNum = $DB->getResult(0, 0) + 1;
} else {
	$myGrpNum = 1;
}

// ���� ������������ ������ ����
$myExamOrder += $myGrpNum;


// �Էµ� ������ ID�� �����´�.
$sql = "SELECT nextval('itemtable_myid_seq')";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("���� ID�� ������ �� �����ϴ�.");
	exit;
}
$itemID = $DB->getResult(0, 0);
//echo $itemID;



// ItemTable�� ������ �����Ѵ�.
// �ܵ������� Group Info�� �ش� ������ �ִ�.
if($_POST["isIndep"] == "N") {
	$itemContents = trim($_POST["getContents"]);
} else {
	$itemContents = "";
}
$sql = "INSERT INTO ItemTable VALUES (" . $itemID . ", '" . $itemContents . "', '" . $myCorrect . "', " .$_POST["myScore"] . ", '" . trim($_POST["getItemInputType"]) . "')";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("�⺻���� ������ �Է��� �� �����ϴ�.");
	exit;
}


// Form�� ���� �����ϸ�
if(trim($_POST["getItemInputType"]) == "N") {
	// OptionTable�� ������ Option�� �����Ѵ�.
	// Option���� ���޵� ��
	// getOptTF1 - 10
	// getOptIMG1 - 10
	for($i=0; $i < $_POST["getOptNo"]; $i++) {
		$myNum = $i + 1;
		$myTF = "getOptTF" . $myNum;
		$myIMG = "getOptIMG" . $myNum;

		//
		// Bio Add 06.07.16 10:23 For ���� ���� ���� 
		//
		// ���� ���ε�
		$myMicrotime = substr(microtime(),strrpos(microtime(),".")+1); // microtime �Ҽ��� �и�
		$sFile = str_replace(" ","",$myMicrotime); // microtime ��������
		$sFileName = "op".trim($sFile).trim(substr($_FILES[$myIMG]["name"], strrpos($_FILES[$myIMG]["name"],"."))); // ���ϸ� ����
		$dir = IMG_PATH."testimages/".trim($_POST["examID"])."/"; // IMG�� �ö� ���
		$dest = $dir."/".$sFileName;
		if(is_uploaded_file(trim($_FILES[$myIMG]["tmp_name"]))) {
			if(!copy(trim($_FILES[$myIMG]["tmp_name"]),$dest)) {
				$fnc->alert("�̹��� ���ε忡 �����Ͽ����ϴ�.");
	        	}
	        	if(!unlink(trim($_FILES[$myIMG]["tmp_name"]))) {
				$fnc->alert("�̹��� ���ε忡 �����Ͽ����ϴ�.");
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
			$fnc->alertBack("���� " . $myNum . "���� �Է��� �� �����ϴ�.");
			exit;
		}
	}
} else {
// �ܺ����Ϸ� ����Ǹ�
	$sql = "INSERT INTO ObjectTable VALUES (nextval('objecttable_myid_seq'), " . $itemID . ", '" . $_FILES["getFile"]["name"]. "')";
	if(!$DB->query($sql)) {
		$fnc->rollbackTrans($DB);
		$fnc->alertBack("�ܺ������� ������ �� �����ϴ�.");
		exit;
	}
}
	
// ItemAInfoTable�� ������ �����Ѵ�.
$sql = "INSERT INTO ItemAInfoTable VALUES (nextval('itemainfotable_myid_seq'), " . $itemID . ", '" . $_POST["getFormat"] . "', '" . $_POST["getCategory"] . "', '" . $_POST["getLevel"] . "', '" . $_POST["getSubject"] . "', '" . trim($_POST["getMeSH"]) . "', '" . $_POST["getCase"] . "', '" . $_POST["getOptType"] . "')";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("�߰����� ������ �Է��� �� �����ϴ�.");
	exit;
}

// ItemGInfoTable�� ���� �����Ѵ�.
// ��� Group�� ��������...
$sql = "INSERT INTO ItemGInfoTable VALUES (" . $itemID . ", "  . $myGrpID . ", " . $myGrpNum . ")";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
//	echo $sql;
	$fnc->alertBack("Group���� ������ �Է��� �� �����ϴ�.");
	exit;
}

// ExamItem �� ���� �����Ѵ�.
$sql =  "INSERT INTO ExamItem VALUES (" . $_POST["examID"] . ", " . $itemID . ", " . $myGrpID . ", " . $myExamOrder . ")";
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("�ش� ������ ���迡 ������ �� �����ϴ�.");
	exit;
}


// ExamAdmin�� myItemNo(���� ���׼�) 1����
$sql = "UPDATE ExamAdmin SET myItemNo = myItemNo + 1 WHERE myID = " . $_POST["examID"];
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("�������׼��� ������ �� �����ϴ�.");
	exit;
}

// �ڽ��� GroupID���� ū�͵��� Order��  1������
$sql = "UPDATE ExamItem SET examOrder = examOrder + 1 WHERE examID = " . $_POST["examID"] . " AND groupID > " . $myGrpID;
if(!$DB->query($sql)) {
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("���� �������׼��� ������ �� �����ϴ�.");
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

