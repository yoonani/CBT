<?
/* =============================================================================
File : joinModiProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 0t
================================================================================
Desc.
	joinModi.php에서 값을 전달받아 DB정보 UPDATE
	UserInfo Table Password 정보 업데이트 가능
	UnivStudent Table studTel, studEmail 정보 업데이트 가능
============================================================================= */
require_once("../include/conf.php");
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
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "D", "F")) ){
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){
//
// 학생 패스워드 검사 
//
	$sql = "select myPassword from UserInfo where myID = '".trim($_SESSION['ID'])."' and myPassword = '".$_POST['userPasswd']."'";
	if(!$DB->query($sql)){
		echo $DB->error();
		exit;
	}
	if($fv->lengthlt(trim($_POST["sno"]), 1)) {
	        $fnc->alertBack("학번을 받아올 수 없습니다");
	        exit;
	}
	if($fv->lengthlt(trim($_POST["userPhone"]), 1)) {
	        $fnc->alertBack("전화번호를 입력하세요");
	        exit;
	}
	if($_POST['newPasswd1']) {
		$newPasswdSql = "update UserInfo set myPassword = '".trim($_POST["newPasswd1"])."' where myID = '".trim($_SESSION['ID'])."'";
	}
}else{
//
// 스탭 패스워드 검사 
//
	$sql = "select myPassword from staffInfo where myID = '".trim($_SESSION['ID'])."' and myPassword = '".$_POST['userPasswd']."'";
	if(!$DB->query($sql)){
		echo $DB->error();
		exit;
	}
	if($_POST['newPasswd1']) {
		$newPasswdSql = "update staffInfo set myPassword = '".trim($_POST["newPasswd1"])."' where myID = '".trim($_SESSION['ID'])."'";
	}
}
if($fv->lengthlt(trim($_POST["email"]), 1)) {
        $fnc->alertBack("E-mail을 입력하세요");
        exit;
}
if(!$cnt = $DB->noRows()){
	$fnc->alertBack("패스워드가 틀렸습니다. 다시 입력해 주세요");
	exit;
}


//
// DB 정보 업데이트
//
if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){

	$sql = "update UnivStudent set studTel = '".trim($_POST["userPhone"])."', studeEmail = '".trim($_POST["email"])."' where univID = '".$_SESSION['UID']."' and univSNO = '".$_POST['sno']."'";
	$fnc->beginTrans($DB);
	if(!$DB->query($sql)){
		$fnc->rollbackTrans($DB);
		$fnc->alertBack("사용자 정보수정에 실패하였습니다.");
		exit;
	}
	if($newPasswdSql){
		if(!$DB->query($newPasswdSql)){
			$fnc->rollbackTrans($DB);
			$fnc->alertBack("사용자 비밀번호변경에 실패하였습니다.");
			exit;
		}
	}
	if($fnc->commitTrans($DB)){
		$fnc->alert("사용자 정보를 수정하였습니다.");
?>
<SCRIPT LANGUAGE="JavaScript">
	location.href = '<?=URL_ROOT?>home/'
</SCRIPT>
<?
	}
}else{
        $sql = "update staffinfo set myEmail = '".trim($_POST["email"])."' where myID = '".trim($_SESSION['ID'])."'";
        $fnc->beginTrans($DB);
        if(!$DB->query($sql)){
                $fnc->rollbackTrans($DB);
                $fnc->alertBack("사용자 정보수정에 실패하였습니다.");
                exit;
        }
        if($newPasswdSql){
                if(!$DB->query($newPasswdSql)){
                        $fnc->rollbackTrans($DB);
                        $fnc->alertBack("사용자 비밀번호변경에 실패하였습니다.");
                        exit;
                }
        }
        if($fnc->commitTrans($DB)){
                $fnc->alert("사용자 정보를 수정하였습니다.");
		ob_end_flush();
?>
<SCRIPT LANGUAGE="JavaScript">
        location.href = '<?=URL_ROOT?>home/'
</SCRIPT>
<?
	}
}
?>
