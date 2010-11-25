<?
/*==============================================================================
File : univAdmin/uAdminAdd.php
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
	GET������� �б� ID�� ���޹޴´�
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
require_once (MY_INCLUDE . "header.php");
$fnc->useMD5();
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
        var form = document.adminReg;
        form.id.value = ignoreSpaces(form.id.value);
        form.name.value = ignoreSpaces(form.name.value);
        form.passwd1.value = ignoreSpaces(form.passwd1.value);
        form.passwd2.value = ignoreSpaces(form.passwd2.value);
        form.rno.value = ignoreSpaces(form.rno.value);
        form.email.value = ignoreSpaces(form.email.value);
        if (form.id.value == null || form.id.value == "" || form.id.value.length < 4 || form.id.value.length > 12) {
                alert("4~12�ڷ� ���̵�(ID)�� �Է����ּ���. �����ڿ� ���ڸ� �����մϴ�");
                form.id.focus();
                return;
        }
        else {
                var ch;
                for (var i=0; i<form.id.value.length; i++) {
                        ch = form.id.value.charAt(i);
                        if ( !(ch >= 'a' && ch <= 'z') && !(ch >= '0' && ch <= '9') && !(ch >= 'A' && ch <= 'Z')  ) {
                                alert("���̵�(ID)�� �Է����ּ���. �����ڿ� ���ڸ� �����մϴ�.");
                                form.id.focus();
                                return;
                        }
                }
        }
        if (form.passwd1.value == null || form.passwd1.value == "" || form.passwd1.value.length < 4) {
                alert("4�� �̻��� ��й�ȣ�� �Է����ּ���.  ");
                form.passwd1.focus();
                return;
        }
        if (form.passwd2.value == null || form.passwd2.value == "" || form.passwd2.value.length < 4 ) {
                alert("��й�ȣ Ȯ���� �Է����ּ���.");
                form.passwd2.focus();
                return;
        }
        if (form.passwd1.value != form.passwd2.value) {
                alert("��й�ȣ�� ��й�ȣ Ȯ���� ��ġ���� �ʽ��ϴ�.");
                form.passwd1.focus();
                return;
        }
        if (form.email.value == null || form.email.value == "") {
                alert("email�� �Է��� �ּ���");
                form.email.focus();
                return;
        }
        if (form.rno.value == null || form.rno.value == "" || form.rno.value.length != 6) {
                alert("�ֹε�Ϲ�ȣ ���ڸ��� �Է����ּ���.");
                form.rno.focus();
                return;
        }
        var password = form.passwd1.value;
        var password2 = form.passwd2.value;
        var encoded_passwd = hex_md5(password);
        var encoded_passwd2 = hex_md5(password2);
        form.passwd1.value = encoded_passwd;
        form.passwd2.value = encoded_passwd2;
        form.submit();
}
function checkID() {
        var form = document.adminReg;
        window.open("checkID.php?userID="+form.id.value,"checkID","height=200,width=300 resizable=no");

}
function isID() {
        var form = document.adminReg;
        if(form.chkID.value == "F" || form.id.value == null || form.id.value == "") {
                if(form.id.value == null || form.id.value == ""){
                        alert("ID�� �Է��ϰ�, ID �ߺ�Ȯ���� ���ּ���");
                }else{
                        alert("ID �ߺ�Ȯ���� ���ּ���");
                }
                form.id.focus();
        }
}
</SCRIPT>
<FORM NAME="adminReg" METHOD="POST" ACTION="uAdminAddProc.php">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="<?=$_GET['univID']?>">
<INPUT TYPE="HIDDEN" NAME="chkID" VALUE="F">
<TABLE BORDER="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2"><img src="<?=IMG_URL?>site/examadmin/title_15_1.gif"></td>
</tr>
<TR>
	<TD width="150" class="htitle" align="center">ID</TD>
	<TD width="440" class="htop"><INPUT TYPE="TEXT" NAME="id"><?$fnc->imgButton(66, 18, "javascript:checkID()", IMG_URL . "site/icon/id_check.gif");?></A></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�̸�</TD>
	<TD class="hmiddle"><INPUT TYPE="TEXT" NAME="name" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">PASSWORD</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="passwd1" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">PASSWORDȮ��</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="passwd2" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�ֹι�ȣ ���ڸ�</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="rno" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">E-mail</TD>
	<TD class="hbottom"><INPUT TYPE="TEXT" NAME="email" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD COLSPAN="2" ALIGN="RIGHT"><A HREF="javascript:check();"><img src="<?=IMG_URL?>site/icon/submit.gif"></A><A HREF="javascript:history.go(-1)"><img src="<?=IMG_URL?>site/icon/cancel.gif"></A></TD>
</TR>
</TABLE>
</FORM>
<?
require_once(MY_INCLUDE."closing.php");
?>
