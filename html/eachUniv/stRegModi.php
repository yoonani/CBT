<?
/* =============================================================================
File : eachUniv/stRegModi.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 07
================================================================================
Desc.
	�б��� ��ϵ� �л� ���� ������
        �б��� ������(F), ��ü ������(A) ���� ����
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

require_once (MY_INCLUDE . "header.php");

$sql = "select univID, univSNO, studName, studDept, studGrade, studRNO1 from UnivStudent where univID = '".$_POST['univID']."' and univSNO = '".$_POST['univSNO']."'"; 
if(!$DB->query($sql)){
	echo $DB->error();
	exit;
}
$univID = $DB->getResult(0,0);
$univSNO = $DB->getResult(0,1);
$studName = $DB->getResult(0,2);
$studDept = $DB->getResult(0,3);
$studGrade = $DB->getResult(0,4);
$studRNO = $DB->getResult(0,5);
?>
<SCRIPT LANGUAGE="JavaScript">
function ignoreSpaces(string) {
        var temp = "";
        string = '' + string;
        splitstring = string.split(" ");
        for(i = 0; i < splitstring.length; i++)
                temp += splitstring[i];
        return temp;
}
function check() {
        var form = document.stRegModi;
        form.dept.value = ignoreSpaces(form.dept.value);
        form.sno.value = ignoreSpaces(form.sno.value);
        form.grade.value = ignoreSpaces(form.grade.value);
        form.name.value = ignoreSpaces(form.name.value);
        form.rno.value = ignoreSpaces(form.rno.value);
        if (form.dept.value == null || form.dept.value == "") {
                alert("�а��� �Է����ּ���.  ");
                form.dept.focus();
                return;
        }
        if (form.sno.value == null || form.sno.value == "") {
                alert("�й��� �Է����ּ���.  ");
                form.sno.focus();
                return;
        }
        if (form.grade.value == null || form.grade.value == "") {
                alert("�г��� �Է����ּ���.  ");
                form.grade.focus();
                return;
        }
        if (form.name.value == null || form.name.value == "") {
                alert("�̸��� �Է����ּ���.  ");
                form.name.focus();
                return;
        }
        if (form.rno.value == null || form.rno.value == "" || form.rno.value.length != 6) {
                alert("�ֹε�Ϲ�ȣ ���ڸ��� �Է����ּ���.");
                form.rno.focus();
                return;
        }
        form.submit();

}
</SCRIPT>

<FORM NAME="stRegModi" METHOD="POST" ACTION="stRegModiProc.php">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="<?=$_POST['univID']?>">
<INPUT TYPE="HIDDEN" NAME="univSNO" VALUE="<?=$_POST['univSNO']?>">
<TABLE BORDER="0" width="590" cellpadding="0" cellspacing="0">
<TR>
	<TD colspan="2"><img src="<?=IMG_URL?>site/examadmin/title_14_1.gif" border="0"></TD>
</TR>
<TR>
	<TD width="150" align="center" class="htitle">�а�</TD>
	<TD width="440" class="htop"><INPUT TYPE="TEXT" NAME="dept" SIZE="10" VALUE="<?=trim($studDept)?>"></TD>
</TR>
<TR>
	<TD align="center" class="htitle">�й�</TD>
	<TD class="hmiddle"><INPUT TYPE="TEXT" NAME="sno" SIZE="10" VALUE="<?=trim($univSNO)?>"></TD>
</TR>
<TR>
	<TD align="center" class="htitle">�г�</TD>
	<TD class="hmiddle"><INPUT TYPE="TEXT" NAME="grade" SIZE="5" VALUE="<?=trim($studGrade)?>"></TD>
</TR>
<TR>
	<TD align="center" class="htitle">�̸�</TD>
	<TD class="hmiddle"><INPUT TYPE="TEXT" NAME="name" SIZE="10" VALUE="<?=trim($studName)?>"></TD>
</TR>
<TR>
	<TD align="center" class="htitle">�ֹε�Ϲ�ȣ ���ڸ�</TD>
	<TD class="hbottom"><INPUT TYPE="TEXT" NAME="rno" SIZE="10" MAXLENGTH="6" VALUE="<?=trim($studRNO)?>"></TD>
</TR>
<TR>
	<TD COLSPAN="2" ALIGN="RIGHT"><A HREF="javascript:check();"><img src="<?=IMG_URL?>site/icon/submit.gif" border="0"></A> <A HREF="javascript:history.go(-1);"><img src="<?=IMG_URL?>site/icon/cancel.gif" border="0"></A></TD>
</TR>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
