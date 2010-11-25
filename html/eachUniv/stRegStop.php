<?
/* =============================================================================
File : eachUniv/stRegStop.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.10 
================================================================================
Desc.
	eachUniv/index.php���� GET������� uid : �б��ڵ�, sno : �й��� �Ѱܹ޾�
	���� ���� ������ ���Ͽ� �ش� �л��� ���� ��Ű�� ������
	
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

if(!$fnc->isPOST()){
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}
//
// ���� ���� $_SESSION['Level'] �� 'A' or 'F' �� ������ �� �ִ�
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "F")) ) {
        $fnc->alertBack("������ �� ���� �����Դϴ�.");
        exit;
}

//
// eachUniv/index.php���� �Ѿ�� �� Ȯ��
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("�б������� �޾ƿ� �� �����ϴ�");
        exit;
}
if($fv->lengthlt(trim($_POST["univSNO"]), 1)) {
        $fnc->alertBack("�й������� �޾ƿ� �� �����ϴ�");
        exit;
}

//
// ����� ���� ����
//
$sql = "update UnivStudent set myLevel = 'S' where univID = '".$_POST['univID']."' and univSNO = '".$_POST['univSNO']."'";
if(!$DB->query($sql)){
        $fnc->alertBack("�й� : ".$_POST['univSNO']." �л��� ���� ��ų �� �����ϴ�.");
        exit;
}else{
	$fnc->alert("�й� : ".$_POST['univSNO']." �л��� ������ ��� ���� ���׽��ϴ�.");
?>
	<SCRIPT LANGUAGE="JavaScript">
		location.href = '<?=URL_ROOT?>eachUniv/index.php?univID=<?=trim($_POST['univID'])?>';
	</SCRIPT>
<?
}

ob_end_flush();
?>
