<?
/* =============================================================================
File : univAdmin/addUniv.php
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

//
// ���� ���� ����
//
if(!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
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
require_once (MY_INCLUDE . "header.php");
?>
<FORM NAME="addUniv" METHOD="POST" ACTION="univAddProc.php">
<TABLE BORDER=0 width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="3"><img src="<?=IMG_URL?>site/examadmin/title_14_4.gif"></td>
</tr>
<TR>
	<TD width="150" class="htitle" align="center">�б��ڵ�</TD>
	<TD width="50" class="htop"><INPUT TYPE="TEXT" NAME="univID" SIZE="2" MAXLENGTH="2"></TD><TD WIDTH="380" class="htop"><FONT COLOR="RED"> * ������ �Ұ����Ͽ��� �����ϰ� �Է��Ͽ� �ּ���<BR> * ���� �α��ڱ��� �����մϴ�. ex) �б� �ڵ� : HL</FONT></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�б���</TD>
	<TD class="hbottom"><INPUT TYPE="TEXT" NAME="univTitle" SIZE="10"></TD><TD class="hbottom"><FONT COLOR="RED"> * ex) �Ѹ����б�</FONT></TD>
</TR>
<TR>
	<TD COLSPAN="3" ALIGN="RIGHT"><A hREF="javascript:document.addUniv.submit()"><img src="<?=IMG_URL?>site/icon/submit.gif"></A><A HREF="javascript:history.go(-1)"><img src="<?=IMG_URL?>site/icon/cancel.gif"></A></TD>
</TR>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
