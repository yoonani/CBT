<?
/* =============================================================================
File : univAdmin/univModi.php
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
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("������ �� ���� �����Դϴ�.");
        exit;
}

//
// univAdmin/index.php���� �Ѿ�� �� Ȯ��
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("�б������� �޾ƿ� �� �����ϴ�");
        exit;
}

$sql = "select myCode, myTitle from univInfo where myCode = '".trim($_POST["univID"])."'";
if(!$DB->query($sql)){
        $fnc->alertBack("�б������� �޾ƿ� �� �����ϴ�");
        exit;
}

$myCode = $DB->getResult(0,0);
$myTitle = $DB->getResult(0,1);
require_once (MY_INCLUDE . "header.php");
?>
<FORM NAME="univModi" METHOD="POST" ACTION="univModiProc.php">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="<?=trim($myCode)?>">
<TABLE BORDER="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2">Ÿ��Ʋ ���� �κ�</td>
</tr>
<TR>
	<TD width="150" class="htitle" align="center">�б��ڵ�</TD>
	<TD width="440" class="htop"><?=trim($myCode)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�б���</TD>
	<TD class="hbottom"><INPUT TYPE="TEXT" NAME="univTitle" SIZE="10" VALUE="<?=trim($myTitle)?>"></TD>
</TR>
<TR>
	<TD COLSPAN="2" ALIGN="RIGHT"><A hREF="javascript:document.univModi.submit()"><img src="<?=IMG_URL?>site/icon/submit.gif"></A><A HREF="javascript:history.go(-1)"><img src="<?=IMG_URL?>site/icon/cancel.gif"></A></TD>
</TR>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
