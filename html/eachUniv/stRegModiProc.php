<?
/*==============================================================================
File : eachUni/stRegModiProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 07
================================================================================
Desc.
	stRegModi.php���� ������ ���޹޾� DB���� ����
        �б��� ������(F), ��ü ������(A) ���� ����
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// ���� ���� ����
//
if(!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("������ �� �����ϴ�.1");
        exit;
}
if(!$fnc->isPOST()){
	$fnc->alertBack("���� �� �� �����ϴ�.2");
	exit;
}

//
// ���� ���� $_SESSION['Level'] �� 'A' or 'F' �� ������ �� �ִ�
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
        $fnc->alertBack("���� �� �� �����ϴ�.3");
        exit;
}

//
// stReg.php���� ���޹��� ���� �ùٸ��� �˻�
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("�б������� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["dept"]), 1)) {
        $fnc->alertBack("�а������� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["sno"]), 1)) {
        $fnc->alertBack("�й������� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["grade"]), 1)) {
        $fnc->alertBack("�г������� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["name"]), 1)) {
        $fnc->alertBack("�̸��� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["rno"]), 1)) {
        $fnc->alertBack("�ֹι�ȣ ���ڸ��� �޾ƿ� �� �����ϴ�");
        exit;
}


$sql = "update univstudent set univSNO = '".trim($_POST["sno"])."', studName = '".trim($_POST["name"])."', studDept = '".trim($_POST["dept"])."', studGrade = '".trim($_POST["grade"])."', studRNO1 = '".trim($_POST["rno"])."' where univID  = '".trim($_POST["univID"])."' and univSNO = '".trim($_POST['univSNO'])."'";
if(!$DB->query($sql)){
	echo $DB->error();
	exit;
}else{
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("'<?=$_POST['name']?>'�л��� �����Ͽ����ϴ�.");
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
