<?
/* =============================================================================
File : enterJoin.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
	����� ��� ��
	�б��� �����ڰ� �ش� �б��� �л��� �й�, �а�, �г�, �ֹι�ȣ ���ڸ��� 
	�Է� �� �л����� ID, PASSWORD, ��ȭ��ȣ, E-mail �Է� �� ȸ�������ϴ�
	��
============================================================================= */
require_once("../include/conf.php");
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}
?>
<HTML>
<HEAD>
        <TITLE>[Welcome]</TITLE>
        <LINK REL="stylesheet" TYPE="text/css" HREF="<?=URL_ROOT?>include/css/medcbt.css">
</HEAD>
<BODY TOPMARGIN="0" LEFTMARGIN="0">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="800" height="600">
<!-- Header Part -->
<TR>
        <TD WIDTH="150" HEIGHT="100" BACKGROUND="<?=IMG_URL?>site/skin/top_left.gif"></TD>
        <TD WIDTH="650" HEIGHT="100" BACKGROUND="<?=IMG_URL?>site/skin/top_right.gif"></TD>
</TR>
<!-- Header Part End -->
<TR>
        <TD WIDTH="150" HEIGHT="50" BACKGROUND="<?=IMG_URL?>site/skin/top_left2_join.gif"></TD>
        <TD WIDTH="650" HEIGHT="50" BACKGROUND="<?=IMG_URL?>site/skin/top_right2.gif"></TD>
</TR>
<TR>
        <TD WIDTH="150" VALIGN="TOP" BACKGROUND="<?=IMG_URL?>site/skin/menu_bg.gif">
                <TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="150">
<!-- Menu Part -->
                <TR>
                        <TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu2"><A HREF="<?=URL_ROOT?>enterJoin.php">����ڵ��</A>&nbsp;&nbsp;&nbsp;</TD>
                </TR>
                <TR>
                        <TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu2"><A HREF="<?=URL_ROOT?>find.php">���̵�/�н�����ã��</A>&nbsp;&nbsp;&nbsp;</TD>
                </TR>
<!-- Menu Part End -->
                </TABLE>
        </TD>
<!-- Body Part -->
        <TD WIDTH="650" VALIGN="TOP" BACKGROUND="<?=IMG_URL?>site/skin/main_bg.gif" class="main">


<FORM NAME="enter" METHOD="POST" ACTION="join.php">
<table border="0" cellpadding="0" cellspacing="0" width="590">
	<tr>
		<td colspan="2"><img src="<?=IMG_URL?>site/userjoin/step.gif" border="0"></td>
	</tr>
	<tr>
		<td colspan="2"><img src="<?=IMG_URL?>site/examadmin/title_12.gif" border="0"></td>
	</tr>
	<tr height="20">
		<td width="150" align="center" class="htitle">�б�</td>
		<td width="440" class="htop">
<?
$sql = "SELECT mycode, mytitle FROM univInfo";
if(!$DB->query($sql)) {
	$fnc->alertBack("Query�� ������ �� �����ϴ�.");
	exit;
}
?>
			<SELECT NAME="univID" SIZE="1">
<?
while($result = $DB->fetch()) {
?>
			        <OPTION VALUE="<?=$result[0]?>"><?=$result[1]?></OPTION>
<?
}
?>
			</SELECT>
		</td>
	</tr>
	<tr height="20">
		<td class="htitle" align="center">�й�</td>
		<td class="hmiddle"><INPUT TYPE="TEXT" NAME="univSNO" SIZE="10"></td>
	</tr>
	<tr height="20">
		<td class="htitle" align="center">�ֹι�ȣ ���ڸ�</td>
		<td class="hbottom"><INPUT TYPE="PASSWORD" NAME="studRNO1" MAXLENGTH="6" onkeydown="if(event.keyCode == 13) document.enter.submit();"></td>
	</tr>
	<tr>
		<td colspan="2" align="right">
<?
		$fnc->imgButton(58, 38, "javascript:document.enter.submit()", IMG_URL."site/icon/continu.gif");
?>
		<!--<td colspan="2" align="right"><A HREF="javascript:document.enter.submit()"><img src="<?=IMG_URL?>site/icon/continu.gif" border="0"></A></td>-->
		<td>
	</tr>
</table>
</FORM>
</TD>
<!-- Body Part End -->
</TR>
<TR>
<!-- Bottom Part -->
        <TD WIDTH="800" HEIGHT="70" COLSPAN="2"><IMG SRC="<?=IMG_URL?>site/skin/bottom.gif" WIDTH="800" HEIGHT="100" BORDER="0"></TD>
<!-- Bottom Part End -->
</TR>
</TABLE>
</BODY>
</HTML>

<?
ob_end_flush();
?>

