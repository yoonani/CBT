<?
/* =============================================================================
File : joinModiProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 0t
================================================================================
Desc.
	joinModi.php���� ���� ���޹޾� DB���� UPDATE
	UserInfo Table Password ���� ������Ʈ ����
	UnivStudent Table studTel, studEmail ���� ������Ʈ ����
============================================================================= */
require_once("../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// ���� ���� ����
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}

//
// SESSION LEVEL CHECK
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "D", "F")) ){
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}

if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){
//
// �л� �н����� �˻� 
//
	$sql = "select myPassword from UserInfo where myID = '".trim($_SESSION['ID'])."' and myPassword = '".$_POST['userPasswd']."'";
	if(!$DB->query($sql)){
		echo $DB->error();
		exit;
	}
	if($fv->lengthlt(trim($_POST["sno"]), 1)) {
	        $fnc->alertBack("�й��� �޾ƿ� �� �����ϴ�");
	        exit;
	}
	if($fv->lengthlt(trim($_POST["userPhone"]), 1)) {
	        $fnc->alertBack("��ȭ��ȣ�� �Է��ϼ���");
	        exit;
	}
	if($_POST['newPasswd1']) {
		$newPasswdSql = "update UserInfo set myPassword = '".trim($_POST["newPasswd1"])."' where myID = '".trim($_SESSION['ID'])."'";
	}
}else{
//
// ���� �н����� �˻� 
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
        $fnc->alertBack("E-mail�� �Է��ϼ���");
        exit;
}
if(!$cnt = $DB->noRows()){
	$fnc->alertBack("�н����尡 Ʋ�Ƚ��ϴ�. �ٽ� �Է��� �ּ���");
	exit;
}


//
// DB ���� ������Ʈ
//
if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){

	$sql = "update UnivStudent set studTel = '".trim($_POST["userPhone"])."', studeEmail = '".trim($_POST["email"])."' where univID = '".$_SESSION['UID']."' and univSNO = '".$_POST['sno']."'";
	$fnc->beginTrans($DB);
	if(!$DB->query($sql)){
		$fnc->rollbackTrans($DB);
		$fnc->alertBack("����� ���������� �����Ͽ����ϴ�.");
		exit;
	}
	if($newPasswdSql){
		if(!$DB->query($newPasswdSql)){
			$fnc->rollbackTrans($DB);
			$fnc->alertBack("����� ��й�ȣ���濡 �����Ͽ����ϴ�.");
			exit;
		}
	}
	if($fnc->commitTrans($DB)){
		$fnc->alert("����� ������ �����Ͽ����ϴ�.");
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
                $fnc->alertBack("����� ���������� �����Ͽ����ϴ�.");
                exit;
        }
        if($newPasswdSql){
                if(!$DB->query($newPasswdSql)){
                        $fnc->rollbackTrans($DB);
                        $fnc->alertBack("����� ��й�ȣ���濡 �����Ͽ����ϴ�.");
                        exit;
                }
        }
        if($fnc->commitTrans($DB)){
                $fnc->alert("����� ������ �����Ͽ����ϴ�.");
		ob_end_flush();
?>
<SCRIPT LANGUAGE="JavaScript">
        location.href = '<?=URL_ROOT?>home/'
</SCRIPT>
<?
	}
}
?>
