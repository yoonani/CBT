<?
/*==============================================================================
File : eachUni/stRegModiProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 07
================================================================================
Desc.
	stRegModi.php에서 정보를 전달받아 DB정보 수정
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


$sql = "update univstudent set univSNO = '".trim($_POST["sno"])."', studName = '".trim($_POST["name"])."', studDept = '".trim($_POST["dept"])."', studGrade = '".trim($_POST["grade"])."', studRNO1 = '".trim($_POST["rno"])."' where univID  = '".trim($_POST["univID"])."' and univSNO = '".trim($_POST['univSNO'])."'";
if(!$DB->query($sql)){
	echo $DB->error();
	exit;
}else{
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("'<?=$_POST['name']?>'학생을 수정하였습니다.");
</SCRIPT>
<?
	if($_SESSION['Level'] == 'A'){
?>
<META HTTP-EQUIV='Refresh' CONTENT='0 ; URL="<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST['univID'])?>"'>
<?
	}else{
?>
<META HTTP-EQUIV='Refresh' CONTENT='0 ; URL="<?=URL_ROOT?>eachUniv/"'>
<?
	}
?>
<?
}
ob_end_flush();
?>
