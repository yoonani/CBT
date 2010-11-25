<?
/* =============================================================================
File : univAdmin/addUnivProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.10
================================================================================
Desc.
        학교 등록 페이지
        전체 관리자(A) 접근 가능
============================================================================= */
require_once("../../include/conf.php");
require_once( MY_INCLUDE."frmValid.php");

//
// 직접 접근 제어
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}
if(!$fnc->isPOST()){
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

//
// 권한 제어 $_SESSION['Level'] 이 'A' or 'F' 만 접근할 수 있다
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("접근할 수 없는 권한입니다.");
        exit;
}

//
// univAdmin/addUniv.php에서 받아온 값 확인
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("학교코드정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["univTitle"]), 1)) {
        $fnc->alertBack("학교이름정보를 받아올 수 없습니다");
        exit;
}
$sql = "select myCode from univInfo where myCode = '".trim($_POST["univID"])."'";
if(!$DB->query($sql)){
        $fnc->alertBack("학교를 등록할 수 없습니다");
        exit;
}
$cnt = $DB->noRows();
if($cnt > 0){
        $fnc->alertBack("\'".trim($_POST['univID'])."\' 는 이미 등록되어 있는 학교 코드 입니다");
        exit;
}
$sql = "insert into univinfo values('".trim($_POST["univID"])."','".trim($_POST["univTitle"])."')";
if(!$DB->query($sql)){
        $fnc->alertBack("학교를 등록할 수 없습니다");
        exit;
}else{
	$fnc->alert($_POST['univTitle']."를 등록하였습니다.");
?>
<SCRIPT LANGUAGE="JavaScript">
	location.href='<?=URL_ROOT?>univAdmin/';
</SCRIPT>
<?
}
ob_end_flush();
?>
