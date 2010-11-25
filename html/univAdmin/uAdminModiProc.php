<?
/* =============================================================================
File : uAdminModiProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 11
================================================================================
Desc.
	uAdminModi.php���� ���� ���޹޾� DB���� UPDATE
	UnivStudent Table studTel, studEmail ���� ������Ʈ ����
============================================================================= */
require_once("../../include/conf.php");
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
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ){
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}

if($_POST['newPasswd1']) {
	$newPasswdSql = "update staffInfo set myPassword = '".trim($_POST["newPasswd1"])."' where myID = '".trim($_POST['myID'])."'";
}
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("�б� �ڵ带 �޾ƿ� �� �����ϴ�.");
        exit;
}
if($fv->lengthlt(trim($_POST["myID"]), 1)) {
        $fnc->alertBack("ID �� �޾ƿ� �� �����ϴ�.");
        exit;
}
if($fv->lengthlt(trim($_POST["id"]), 1)) {
        $fnc->alertBack("ID �� �޾ƿ� �� �����ϴ�.");
        exit;
}
if($fv->lengthlt(trim($_POST["name"]), 1)) {
        $fnc->alertBack("�̸��� �Է��ϼ���");
        exit;
}
if($fv->lengthlt(trim($_POST["rno"]), 1)) {
        $fnc->alertBack("�ֹι�ȣ ���ڸ��� �Է��ϼ���");
        exit;
}
if($fv->lengthlt(trim($_POST["email"]), 1)) {
        $fnc->alertBack("E-mail�� �Է��ϼ���");
        exit;
}


//
// DB ���� ������Ʈ
//
$sql = "update staffinfo set myID = '".trim($_POST["id"])."', myName = '".trim($_POST["name"])."', myRNO1 = '".trim($_POST["rno"])."', myEmail = '".trim($_POST["email"])."' where myID = '".trim($_POST["myID"])."'";
$fnc->beginTrans($DB);
if(!$DB->query($sql)){
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("���������� �����Ͽ����ϴ�.");
	exit;
}
if($newPasswdSql){
	if(!$DB->query($newPasswdSql)){
		$fnc->rollbackTrans($DB);
		$fnc->alertBack("��й�ȣ���濡 �����Ͽ����ϴ�.");
		exit;
	}
}
if($fnc->commitTrans($DB)){
	$fnc->alert("������ �����Ͽ����ϴ�.");
?>
<SCRIPT LANGUAGE="JavaScript">
	location.href = '<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST['univID'])?>'
</SCRIPT>
<?
}
?>
