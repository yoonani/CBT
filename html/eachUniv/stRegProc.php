<?
/*==============================================================================
File : eachUni/stRegProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 07
================================================================================
Desc.
	stReg.php에서 정보를 전달받아 DB에 입력
        학교별 관리자(F), 전체 관리자(A) 접근 가능
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// 직접 접근 제어
//
if(!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.1");
        exit;
}
if(!$fnc->isPOST()){
	$fnc->alertBack("접근 할 수 없습니다.2");
	exit;
}

//
// 권한 제어 $_SESSION['Level'] 이 'A' or 'F' 만 접근할 수 있다
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
        $fnc->alertBack("접근 할 수 없습니다.3");
        exit;
}

//
// stReg.php에서 전달받은 값이 올바른지 검사
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("학교정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["dept"]), 1)) {
        $fnc->alertBack("학과정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["sno"]), 1)) {
        $fnc->alertBack("학번정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["grade"]), 1)) {
        $fnc->alertBack("학년정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["name"]), 1)) {
        $fnc->alertBack("이름을 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["rno"]), 1)) {
        $fnc->alertBack("주민번호 앞자리를 받아올 수 없습니다");
        exit;
}
//
// 등록된 사용자 점검
//
$sql = "select studName from univstudent where univID = '".trim($_POST["univID"])."' and univSNO = '".trim($_POST["sno"])."'";
if(!$DB->query($sql)){
        echo $DB->error();
        exit;
}
if($DB->noRows()){
	$fnc->alertBack(trim($_POST["sno"])." 은 이미 등록되어 있습니다.");
	exit;
}
//
// myLevel 은 DB에서 Default 로 'B' 로 되어있음
//
$sql = "insert into univstudent(univID, univSNO, studName, studDept, studGrade, studRNO1) values ('".trim($_POST["univID"])."','".trim($_POST["sno"])."','".trim($_POST["name"])."','".trim($_POST["dept"])."','".trim($_POST["grade"])."','".trim($_POST["rno"])."')";
if(!$DB->query($sql)){
	echo $DB->error();
	exit;
}else{
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("'<?=$_POST['name']?>'학생을 등록하였습니다.");
</SCRIPT>
<?
//
// 전체 관리자의 경우 학교 리스트에서 학생관리로 들어가기 때문에 학교코드를 GET 방식으로 받아오기때문에 현재 학교코드를 다시 넘겨준다.
//
	if($_SESSION['Level'] == 'A'){
?>
<META HTTP-EQUIV='Refresh' CONTENT='0 ; URL="<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST["univID"])?>"'>
<?
	}else{
?>
<META HTTP-EQUIV='Refresh' CONTENT='0 ; URL="<?=URL_ROOT?>eachUniv"'>
<?
	}
?>
<?
}
?>
