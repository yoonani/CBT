<?
/* =============================================================================
File : joinProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 07
================================================================================
Desc.
	join.php에서 값을 전달 받음
	회원가입의 최종 단계
	DB에 회원정보를 입력
	해당 학교의 해당 학번에 대한 정보를 DB에 UPDATE, INSERT 시킨다
	UnivStudent Table의 정보를 UPDATE
	UserInfo Table에 정보 INSERT 
============================================================================= */
require_once("../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// 직접 접근 검사
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
// join.php에서 전달받은 값이 올바른지 검사
//
if($fv->lengthlt(trim($_POST["univSNO"]), 1)) {
        $fnc->alertBack("학번을 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("학교정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["userID"]), 1)) {
        $fnc->alertBack("ID를 입력하세요");
        exit;
}
if($fv->lengthlt(trim($_POST["userPasswd1"]), 1)) {
        $fnc->alertBack("패스워드를 입력하세요");
        exit;
}
if($fv->lengthlt(trim($_POST["userPasswd2"]), 1)) {
        $fnc->alertBack("패스워드확인을 입력하세요");
        exit;
}
if($fv->lengthlt(trim($_POST["userPhone"]), 1)) {
        $fnc->alertBack("전화번호를 입력하세요");
        exit;
}
if($fv->lengthlt(trim($_POST["email"]), 1)) {
        $fnc->alertBack("전화번호를 입력하세요");
        exit;
}
if(trim($_POST["userPasswd1"]) != trim($_POST["userPasswd2"])){
        $fnc->alertBack("비밀번호와 비밀번호확인이 틀립니다 다시 입력하여 주세요");
	exit;
}
$sqlUnivStudent = "update UnivStudent set studtel = '".trim($_POST["userPhone"])."', studeemail = '".trim($_POST["email"])."' where univID = '".trim($_POST["univID"])."' and univSNO = '".trim($_POST["univSNO"])."'";
$sqlUserInfo = "insert into userinfo(myID, myPassword, univID, univSNO) values ('".trim($_POST["userID"])."','".trim($_POST["userPasswd1"])."','".trim($_POST["univID"])."','".trim($_POST["univSNO"])."')";

$fnc->beginTrans($DB);
if(!$DB->query($sqlUnivStudent)){
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("사용자 정보를 업데이트 할 수 없습니다");
}
if(!$DB->query($sqlUserInfo)){
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("사용자 정보를 추가할 수 없습니다");
}
if($fnc->commitTrans($DB)){
	$fnc->alert("사용자 등록이 완료 되었습니다. 로그인 후 사용하세요");
?>
<SCRIPT LANGUAGE="JavaScript">
	location.href = '<?=URL_ROOT?>';
</SCRIPT>
<?
}else{
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("사용자 정보를 추가할 수 없습니다");
}
ob_end_flush();
?>

