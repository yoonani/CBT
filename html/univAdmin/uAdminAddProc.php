<?
/*==============================================================================
File : univAdmin/uAdminAddProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.11
================================================================================
Desc.
        �б��� ������ �߰� ������
        ��ü �����ڰ� �б��� ������ �б��� �����Ͽ� �����ϸ�, �б� �����ڰ�
        ��ϵǾ� ���� ���� ��� ��ü �����ڰ� �б� �����ڸ� ��Ͻ�ų �� �ִ�
        �������̴�.
	uAdminAdd.php���� �б��� �������� ������ �޾ƿ� DB�� �����Ѵ�.
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");


//
// ���� ���� �˻�
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}
//
// ���� ���� $_SESSION['Level'] �� 'A' or 'F' �� ������ �� �ִ�
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("������ �� ���� �����Դϴ�.");
        exit;
}
//
// uAdminAdd.php���� ���޹��� ���� �ùٸ��� �˻�
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("�б������� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["id"]), 1)) {
        $fnc->alertBack("ID�� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["name"]), 1)) {
        $fnc->alertBack("�̸��� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["passwd1"]), 1)) {
        $fnc->alertBack("PASSWORD �� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["passwd2"]), 1)) {
        $fnc->alertBack("PASSWORD �� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["email"]), 1)) {
        $fnc->alertBack("E-mail �� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["rno"]), 1)) {
        $fnc->alertBack("�ֹι�ȣ ���ڸ��� �޾ƿ� �� �����ϴ�");
        exit;
}
if(trim($_POST["passwd1"]) != trim($_POST["passwd2"])){
        $fnc->alertBack("��й�ȣ�� ��й�ȣȮ���� Ʋ���ϴ� �ٽ� �Է��Ͽ� �ּ���");
        exit;
}

//
// ��ϵ� ID ����
//
$sql = "select myID from staffinfo where myScode = '".trim($_POST["univID"])."' and myID = '".trim($_POST["id"])."'";
if(!$DB->query($sql)){
        echo $DB->error();
        exit;
}
if($DB->noRows()){
        $fnc->alertBack(trim($_POST["id"])." �� �̹� ��ϵǾ� �ֽ��ϴ�.");
        exit;
}

$sql = "insert into StaffInfo(myID, myPassword, myLevel, myScode, myName, myPosition, myRNO1, myEmail) values ('".trim($_POST["id"])."','".trim($_POST["passwd1"])."','F' ,'".trim($_POST["univID"])."','".trim($_POST["name"])."', '�б��� ������' ,'".trim($_POST["rno"])."','".trim($_POST["email"])."')";
if(!$DB->query($sql)){
        echo $DB->error();
        exit;
}else{
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("'<?=$_POST['name']?>'�� ����Ͽ����ϴ�.");
</SCRIPT>
<META HTTP-EQUIV='Refresh' CONTENT='0 ; URL="<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST["univID"])?>"'>
<?
}
?>
