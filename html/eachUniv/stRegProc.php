<?
/*==============================================================================
File : eachUni/stRegProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 07
================================================================================
Desc.
	stReg.php���� ������ ���޹޾� DB�� �Է�
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
//
// ��ϵ� ����� ����
//
$sql = "select studName from univstudent where univID = '".trim($_POST["univID"])."' and univSNO = '".trim($_POST["sno"])."'";
if(!$DB->query($sql)){
        echo $DB->error();
        exit;
}
if($DB->noRows()){
	$fnc->alertBack(trim($_POST["sno"])." �� �̹� ��ϵǾ� �ֽ��ϴ�.");
	exit;
}
//
// myLevel �� DB���� Default �� 'B' �� �Ǿ�����
//
$sql = "insert into univstudent(univID, univSNO, studName, studDept, studGrade, studRNO1) values ('".trim($_POST["univID"])."','".trim($_POST["sno"])."','".trim($_POST["name"])."','".trim($_POST["dept"])."','".trim($_POST["grade"])."','".trim($_POST["rno"])."')";
if(!$DB->query($sql)){
	echo $DB->error();
	exit;
}else{
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("'<?=$_POST['name']?>'�л��� ����Ͽ����ϴ�.");
</SCRIPT>
<?
//
// ��ü �������� ��� �б� ����Ʈ���� �л������� ���� ������ �б��ڵ带 GET ������� �޾ƿ��⶧���� ���� �б��ڵ带 �ٽ� �Ѱ��ش�.
//
	if($_SESSION['Level'] == 'A'){
?>
<META HTTP-EQUIV='Refresh' CONTENT='0 ; URL="<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST["univID"])?>"'>
<?
	}else{
?>
<META HTTP-EQUIV='Refresh' CONTENT='0 ; URL="<?=URL_ROOT?>eachUniv"'>
<?
	}
?>
<?
}
?>
