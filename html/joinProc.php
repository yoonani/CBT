<?
/* =============================================================================
File : joinProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 07
================================================================================
Desc.
	join.php���� ���� ���� ����
	ȸ�������� ���� �ܰ�
	DB�� ȸ�������� �Է�
	�ش� �б��� �ش� �й��� ���� ������ DB�� UPDATE, INSERT ��Ų��
	UnivStudent Table�� ������ UPDATE
	UserInfo Table�� ���� INSERT 
============================================================================= */
require_once("../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// ���� ���� �˻�
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}
if(!$fnc->isPOST()){
	$fnc->alertBack("������ �� �����ϴ�.");
	exit;
}

//
// join.php���� ���޹��� ���� �ùٸ��� �˻�
//
if($fv->lengthlt(trim($_POST["univSNO"]), 1)) {
        $fnc->alertBack("�й��� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("�б������� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["userID"]), 1)) {
        $fnc->alertBack("ID�� �Է��ϼ���");
        exit;
}
if($fv->lengthlt(trim($_POST["userPasswd1"]), 1)) {
        $fnc->alertBack("�н����带 �Է��ϼ���");
        exit;
}
if($fv->lengthlt(trim($_POST["userPasswd2"]), 1)) {
        $fnc->alertBack("�н�����Ȯ���� �Է��ϼ���");
        exit;
}
if($fv->lengthlt(trim($_POST["userPhone"]), 1)) {
        $fnc->alertBack("��ȭ��ȣ�� �Է��ϼ���");
        exit;
}
if($fv->lengthlt(trim($_POST["email"]), 1)) {
        $fnc->alertBack("��ȭ��ȣ�� �Է��ϼ���");
        exit;
}
if(trim($_POST["userPasswd1"]) != trim($_POST["userPasswd2"])){
        $fnc->alertBack("��й�ȣ�� ��й�ȣȮ���� Ʋ���ϴ� �ٽ� �Է��Ͽ� �ּ���");
	exit;
}
$sqlUnivStudent = "update UnivStudent set studtel = '".trim($_POST["userPhone"])."', studeemail = '".trim($_POST["email"])."' where univID = '".trim($_POST["univID"])."' and univSNO = '".trim($_POST["univSNO"])."'";
$sqlUserInfo = "insert into userinfo(myID, myPassword, univID, univSNO) values ('".trim($_POST["userID"])."','".trim($_POST["userPasswd1"])."','".trim($_POST["univID"])."','".trim($_POST["univSNO"])."')";

$fnc->beginTrans($DB);
if(!$DB->query($sqlUnivStudent)){
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("����� ������ ������Ʈ �� �� �����ϴ�");
}
if(!$DB->query($sqlUserInfo)){
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("����� ������ �߰��� �� �����ϴ�");
}
if($fnc->commitTrans($DB)){
	$fnc->alert("����� ����� �Ϸ� �Ǿ����ϴ�. �α��� �� ����ϼ���");
?>
<SCRIPT LANGUAGE="JavaScript">
	location.href = '<?=URL_ROOT?>';
</SCRIPT>
<?
}else{
	$fnc->rollbackTrans($DB);
	$fnc->alertBack("����� ������ �߰��� �� �����ϴ�");
}
ob_end_flush();
?>

