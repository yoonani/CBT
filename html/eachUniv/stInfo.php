<?
/* =============================================================================
File : eachUniiv/stInfo.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 13
================================================================================
Desc.
        �б��� �л� ���� ����  ������
        �б��� ������(F), ��ü ������(A) ���� ����
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// â���� ���� ���� �� ����
//
?>
<HTML>
<HEAD>
<TITLE><?=TITLE?></TITLE>
<LINK REL="stylesheet" TYPE="text/css" HREF="<?=URL_ROOT?>include/css/medcbt.css">
</HEAD>
<SCRIPT LANGUAGE="JavaScript">
if(window.name != 'stInfo') {
        alert('������ �� �����ϴ�');
        window.close();
}
</SCRIPT>
<?
//
// �θ�â eachUniv/index.php���� �޾ƿ� ����
//
if($fv->lengthlt($_POST["univSNO"], 1)) {
        $fnc->alertBack("�й��� �޾ƿ� �� �����ϴ�.");
?>
<SCRIPT LANGUAGE="JavaScript">
        self.close();
</SCRIPT>
<?
}
if($fv->lengthlt($_POST["univID"], 1)) {
        $fnc->alertBack("�б��� �޾ƿ� �� �����ϴ�.");
?>
<SCRIPT LANGUAGE="JavaScript">
        self.close();
</SCRIPT>
<?
}

//
// DB���� �ش� �л� ���� ���� ����
//
$sql = "select univSNO, studName, studDept, studGrade, studTel, studRNO1, studeemail from UnivStudent where univID = '".trim($_POST["univID"])."' and univSNO = '".trim($_POST["univSNO"])."'";
if(!$DB->query($sql)){
	echo $DB->error();
	exit;
}
$univSNO = $DB->getResult(0,0);
$studName = $DB->getResult(0,1);
$studDept = $DB->getResult(0,2);
$studGrade = $DB->getResult(0,3);
$studTel = $DB->getResult(0,4);
$studRNO1 = $DB->getResult(0,5);
$studeemail = $DB->getResult(0,6);
?>
<BODY>
<TABLE BORDER="0" width="300" height="240" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2"><img src="<?=IMG_URL?>site/examadmin/title_20.gif"></td>
</tr>
<TR>
	<TD width="170" class="htitle" align="center">�а�</TD>
	<TD width="130" class="htop"><?=trim($studGrade)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�й�</TD>
	<TD class="hmiddle"><?=trim($univSNO)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�̸�</TD>
	<TD class="hmiddle"><?=trim($studName)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�г�</TD>
	<TD class="hmiddle"><?=trim($studGrade)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">��ȭ��ȣ</TD>
	<TD class="hmiddle"><?=trim($studTel)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�ֹε�Ϲ�ȣ ���ڸ�</TD>
	<TD class="hmiddle"><?=trim($studRNO1)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">E-mail</TD>
	<TD class="hbottom"><?=trim($studeemail)?></TD>
</TR>
<TR>
	<TD COLSPAN="2" ALIGN="RIGHT"><A HREF="javascript:window.close()"><img src="<?=IMG_URL?>site/icon/submit.gif"></A></TD>
</TR>
</TABLE>
</BODY>
</HTML>
