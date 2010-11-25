<?
/* =============================================================================
File : univAdmin/addUnivProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.10
================================================================================
Desc.
        �б� ��� ������
        ��ü ������(A) ���� ����
============================================================================= */
require_once("../../include/conf.php");
require_once( MY_INCLUDE."frmValid.php");

//
// ���� ���� ����
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
// ���� ���� $_SESSION['Level'] �� 'A' or 'F' �� ������ �� �ִ�
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("������ �� ���� �����Դϴ�.");
        exit;
}

//
// univAdmin/addUniv.php���� �޾ƿ� �� Ȯ��
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("�б��ڵ������� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["univTitle"]), 1)) {
        $fnc->alertBack("�б��̸������� �޾ƿ� �� �����ϴ�");
        exit;
}
$sql = "select myCode from univInfo where myCode = '".trim($_POST["univID"])."'";
if(!$DB->query($sql)){
        $fnc->alertBack("�б��� ����� �� �����ϴ�");
        exit;
}
$cnt = $DB->noRows();
if($cnt > 0){
        $fnc->alertBack("\'".trim($_POST['univID'])."\' �� �̹� ��ϵǾ� �ִ� �б� �ڵ� �Դϴ�");
        exit;
}
$sql = "insert into univinfo values('".trim($_POST["univID"])."','".trim($_POST["univTitle"])."')";
if(!$DB->query($sql)){
        $fnc->alertBack("�б��� ����� �� �����ϴ�");
        exit;
}else{
	$fnc->alert($_POST['univTitle']."�� ����Ͽ����ϴ�.");
?>
<SCRIPT LANGUAGE="JavaScript">
	location.href='<?=URL_ROOT?>univAdmin/';
</SCRIPT>
<?
}
ob_end_flush();
?>
