<?
/* =============================================================================
File : join.php
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
require_once(MY_INCLUDE . "frmValid.php");


//
// ���� ���� �˻�
//
if(!$fnc->isPOST()){
	$fnc->alertBack("������ �� �����ϴ�.");
	exit;
}
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}

//
// enterJoin.php���� ���޹��� �� Ȯ��
//
if($fv->lengthlt($_POST["univSNO"], 1)) {
        $fnc->alertBack("�й��� �Է��ϼ���");
        exit;
}
if($fv->lengthlt($_POST["univID"], 1)) {
        $fnc->alertBack("�б��� �����ϼ���");
        exit;
}
if($fv->lengthlt($_POST["studRNO1"], 6)) {
        $fnc->alertBack("�ֹε�Ϲ�ȣ ���ڸ��� Ȯ���� �ּ���");
        exit;
}


//
// UnivStudent Table �˻� ����
//
$sql = "select studName, studDept, studGrade from UnivStudent where univID = '".$_POST['univID']."' and univSNO = '".$_POST['univSNO']."' and studRNO1 = '".$_POST['studRNO1']."'";

$checkIDsql = "select myID from UserInfo where univID = '".$_POST['univID']."' and univSNO = '".$_POST['univSNO']."'";

if(!$DB->query($sql)){
	echo $DB->error();
	$fnc->alertBack("�л������� �ҷ��� �� �����ϴ�. ����� �ٽ� �õ��ϼ���");
}
if($cnt = $DB->noRows() <  1){
	$fnc->alertBack("�л� ������ �����ϴ�. �ٽ� �Է��ϼ���");
}
$myName = $DB->getResult(0,0);
$myDept = $DB->getResult(0,1);
$myGrad = $DB->getResult(0,2);

if(!$DB->query($checkIDsql)){
	echo $DB->error();
	$fnc->alertBack("�л������� �ҷ��� �� �����ϴ�. ����� �ٽ� �õ��ϼ���");
}
if($cnt = $DB->noRows()){
	$myID = $DB->getResult(0,0);
	$fnc->alertBack("�̹� ID : ".trim($myID)." �� ��ϵǾ� �ֽ��ϴ�");
}
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
        var form = document.join;
        form.userID.value = ignoreSpaces(form.userID.value);
        form.userPasswd1.value = ignoreSpaces(form.userPasswd1.value);
        form.userPasswd2.value = ignoreSpaces(form.userPasswd2.value);
        form.email.value = ignoreSpaces(form.email.value);
        form.userPhone.value = ignoreSpaces(form.userPhone.value);
	if (form.userID.value == null || form.userID.value == "" || form.userID.value.length < 4 || form.userID.value.length > 10) {
                alert("4~10�ڷ� ���̵�(ID)�� �Է����ּ���. �����ڿ� ���ڸ� �����մϴ�");
                form.userID.focus();
                return;
        }
        else {
                var ch;
                for (var i=0; i<form.userID.value.length; i++) {
                        ch = form.userID.value.charAt(i);
                        if ( !(ch >= 'a' && ch <= 'z') && !(ch >= '0' && ch <= '9') && !(ch >= 'A' && ch <= 'Z') ) {
                                alert("���̵�(ID)�� �Է����ּ���. �����ڿ� ���ڸ� �����մϴ�.");
                                form.userID.focus();
                                return;
                        }
                }
        }
	if (form.userPasswd1.value == null || form.userPasswd1.value == "" || form.userPasswd1.value.length < 4) {
                alert("4�� �̻��� ��й�ȣ�� �Է����ּ���.  ");
                form.userPasswd1.focus();
                return;
        }
        if (form.userPasswd2.value == null || form.userPasswd2.value == "" || form.userPasswd2.value.length < 4) {
                alert("��й�ȣ Ȯ���� �Է����ּ���.");
                form.userPasswd2.focus();
                return;
        }
        if (form.userPasswd1.value != form.userPasswd2.value) {
                alert("��й�ȣ�� ��й�ȣ Ȯ���� ��ġ���� �ʽ��ϴ�.");
                form.userPasswd1.focus();
                return;
        }
	if (form.userPhone.value == null || form.userPhone.value == "") {
                alert("��ȭ��ȣ�� �Է��� �ּ���");
                form.userPhone.focus();
                return;
        }
	if (form.email.value == null || form.email.value == "") {
                alert("email�� �Է��� �ּ���");
                form.email.focus();
                return;
        }
	var password = form.userPasswd1.value;
        var password2 = form.userPasswd2.value;
        var encoded_passwd = hex_md5(password);
        var encoded_passwd2 = hex_md5(password2);
        form.userPasswd1.value = encoded_passwd;
        form.userPasswd2.value = encoded_passwd2;
        form.submit();

}
function checkID() {
        var form = document.join;
        window.open("checkID.php?userID="+form.userID.value,"checkID","height=200,width=300 resizable=no");

}
function isID() {
	var form = document.join;
	if(form.chkID.value == "F" || form.userID.value == null || form.userID.value == "") {
		if(form.userID.value == null || form.userID.value == ""){
			alert("ID�� �Է��ϰ�, ID �ߺ�Ȯ���� ���ּ���");
		}else{
			alert("ID �ߺ�Ȯ���� ���ּ���");
		}
		form.userID.focus();
	}
}
</SCRIPT>
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
                        <TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu2"><A HREF="<?=URL_ROOT?>enterJoin.php">����ڵ��</a>&nbsp;&nbsp;&nbsp;</TD>
                </TR>
                <TR>
                        <TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu2"><A HREF="<?=URL_ROOT?>find.php">���̵�/�н�����ã��</a>&nbsp;&nbsp;&nbsp;</TD>
                </TR>
<!-- Menu Part End -->
                </TABLE>
        </TD>
<!-- Body Part -->
        <TD WIDTH="650" VALIGN="TOP" BACKGROUND="<?=IMG_URL?>site/skin/main_bg.gif" class="main">

<FORM NAME="join" METHOD="post" ACTION="joinProc.php">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="<?=$_POST['univID']?>">
<INPUT TYPE="HIDDEN" NAME="univSNO" VALUE="<?=$_POST['univSNO']?>">
<INPUT TYPE="HIDDEN" NAME="chkID" VALUE="F">
<TABLE BORDER=0 width="590" cellpadding="0" cellspacing="0">
<TR>
	<TD COLSPAN="2"><img src="<?=IMG_URL?>site/userjoin/step2.gif" border="0"></TD>
</TR>
<TR>
	<TD COLSPAN="2"><img src="<?=IMG_URL?>site/examadmin/title_13.gif" border="0"></TD>
</TR>
<TR>
	<TD width="150" class="htitle" align="center">ID</TD>
	<TD width="440" class="htop" align="absmiddle"><INPUT TYPE="TEXT" NAME="userID"><?$fnc->imgButton(66, 18, "javascript:checkID()", IMG_URL . "site/icon/id_check.gif");?></A><FONT COLOR="RED"> * 4�ڿ��� 10�ڷ� ��� �� ���� �Ұ�</FONT></TD>
</TR>
<TR>
	<TD class="htitle" align="center">PASSWORD</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="userPasswd1" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">PASSWORD Ȯ��</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="userPasswd2" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�̸�</TD>
	<TD class="hmiddle"><?=trim($myName)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�й�</TD>
	<TD class="hmiddle"><?=trim($_POST['univSNO'])?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�а�</TD>
	<TD class="hmiddle"><?=trim($myDept)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">�г�</TD>
	<TD class="hmiddle"><?=trim($myGrad)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">��ȭ��ȣ</TD>
	<TD class="hmiddle">
		<INPUT TYPE="TEXT" NAME="userPhone" MAXLENGTH="14" SIZE="14" OnClick="isID()" READONLY>
	</TD>
</TR>
<TR>
	<TD class="htitle" align="center">E-mail</TD>
	<TD class="hbottom"><FONT COLOR="RED"><INPUT TYPE="TEXT" NAME="email" SIZE="14" OnClick="isID()" READONLY> * �н�����н� �� �߼۵� Email �ּ�(�б� ���� ����)</FONT></TD>
</TR>
<TR>
	<td COLSPAN="2" ALIGN="RIGHT">
<?
$fnc->imgButton(98, 38, "javascript:check()", IMG_URL . "site/icon/ok.gif");
?>
	</td>
	<!--<TD COLSPAN="2" ALIGN="RIGHT"><A HREF="javascript:check()"><img src="<?=IMG_URL?>site/icon/ok.gif" border="0"></A></TD>-->
</TR>
</TABLE>
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

</FORM>
<?
ob_end_flush();
?>
