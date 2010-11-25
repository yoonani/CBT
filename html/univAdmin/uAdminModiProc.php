<?
/* =============================================================================
File : uAdminModiProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 11
================================================================================
Desc.
	uAdminModi.php에서 값을 전달받아 DB정보 UPDATE
	UnivStudent Table studTel, studEmail 정보 업데이트 가능
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// 직접 접근 제어
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

//
// SESSION LEVEL CHECK
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ){
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

if($_POST['newPasswd1']) {
	$newPasswdSql = "update staffInfo set myPassword = '".trim($_POST["newPasswd1"])."' where myID = '".trim($_POST['myID'])."'";
}
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("학교 코드를 받아올 수 없습니다.");
        exit;
}
if($fv->lengthlt(trim($_POST["myID"]), 1)) {
        $fnc->alertBack("ID 를 받아올 수 없습니다.");
        exit;
}
if($fv->lengthlt(trim($_POST["id"]), 1)) {
        $fnc->alertBack("ID 를 받아올 수 없습니다.");
        exit;
}
if($fv->lengthlt(trim($_POST["name"]), 1)) {
        $fnc->alertBack("이름을 입력하세요");
        exit;
}
if($fv->lengthlt(trim($_POST["rno"]), 1)) {
        $fnc->alertBack("주민번호 앞자리를 입력하세요");
        exit;
}
if($fv->lengthlt(trim($_POST["email"]), 1)) {
        $fnc->alertBack("E-mail을 입력하세요");
        exit;
}


//
// DB 정보 업데이트
//
$sql = "update staffinfo set myID = '".trim($_POST["id"])."', myName = '".trim($_POST["name"])."', myRNO1 = '".trim($_POST["rno"])."', myEmail = '".trim($_POST["email"])."' where myID = '".trim($_POST["myID"])."'";
$fnc->beginTrans($DB);
if(!$DB->query($sql)){
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("정보수정에 실패하였습니다.");
	exit;
}
if($newPasswdSql){
	if(!$DB->query($newPasswdSql)){
		$fnc->rollbackTrans($DB);
		$fnc->alertBack("비밀번호변경에 실패하였습니다.");
		exit;
	}
}
if($fnc->commitTrans($DB)){
	$fnc->alert("정보를 수정하였습니다.");
?>
<SCRIPT LANGUAGE="JavaScript">
	location.href = '<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST['univID'])?>'
</SCRIPT>
<?
}
?>
