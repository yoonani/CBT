<?php
require_once("../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");
if($fv->lengthlt($_POST["getID"], 1)) {
	$fnc->alertBack("ID를 입력하세요");
	exit;
}
if($fv->lengthlt($_POST["getPWD"], 1)) {
	$fnc->alertBack("암호를 입력하세요");
	exit;
}

$sql = "SELECT a.myPassword as password, b.myLevel as level, a.myID as ID, b.studName as Name, b.univID as UID FROM UserInfo as a NATURAL JOIN UnivStudent as b WHERE myID = '" . trim($_POST["getID"]) . "'" ;
$sql .= "  UNION ";
$sql .= "SELECT myPassword, myLevel, myID, myName, myScode FROM StaffInfo WHERE myID = '" . trim($_POST["getID"]) . "'" ;
if(!$DB->query($sql)) {
	echo $DB->error();
	$fnc->alertBack("로그인 과정에 문제가 발생했습니다.\\n잠시후 다시 접속해 주세요");
	
}

if($DB->noRows() < 1) {
	$fnc->alertBack("존재하지 않는 ID입니다.");
}

$myPWD = trim($DB->getResult(0, 0));
$myLevel = trim($DB->getResult(0, 1));
$myID = trim($DB->getResult(0, 2));
$myName = trim($DB->getResult(0, 3));
$myUID = trim($DB->getResult(0, 4));
//echo $myLevel;
//echo $myID;


if(strcmp($myPWD, $_POST["getPWD"])) {
	$fnc->alertBack("암호 입력이 잘못되었습니다.");
} else {
//	$_SESSION["logIn"] = "yes";
	$_SESSION["Level"] = $myLevel;
	$_SESSION["ID"] = $myID;
	$_SESSION["Name"] = $myName;
	$_SESSION["UID"] = $myUID;
	echo "
	<META HTTP-EQUIV='refresh' CONTENT='0;URL=" . URL_ROOT . "home/index.php" . "'>";
}
	


require_once(MY_INCLUDE . "closing.php");
?>
