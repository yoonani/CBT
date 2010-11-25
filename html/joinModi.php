<?
/* =============================================================================
File : joinModi.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
        ����� ���� ��
        �л����� PASSWORD, ��ȭ��ȣ, E-mail  ���� ����
============================================================================= */
require_once("../include/conf.php");
require_once (MY_INCLUDE . "header.php");


//
// ���� ���� ����
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("������ �� �����ϴ�.");
        exit;
}

//
// SESSION LEVEL CHECK
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "D", "F")) ){
	$fnc->alertBack("������ �� �����ϴ�.");
	exit;
}

//
// ����� ���� DB���� ����
//
if($fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){
	$sql = "select myEmail from staffinfo where myID = '".trim($_SESSION['ID'])."'";
	if(!$DB->query($sql)){
                echo $DB->error();
                exit;
        }
        $email = $DB->getResult(0,0);

}else{
	$sql ="select us.univSNO, us.studDept, us.studGrade, us.studTel, us.studeEmail from UnivStudent us join Userinfo ui on us.univsno = ui.univsno where ui.myID = '".$_SESSION['ID']."'";
	if(!$DB->query($sql)){
		echo $DB->error();
		exit;
	}
	$sno = $DB->getResult(0,0);
	$dept = $DB->getResult(0,1);
	$grade = $DB->getResult(0,2);
	$tel = $DB->getResult(0,3);
	$email = $DB->getResult(0,4);
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
        var form = document.joinModi;
        form.newPasswd1.value = ignoreSpaces(form.newPasswd1.value);
        form.newPasswd2.value = ignoreSpaces(form.newPasswd2.value);
        form.email.value = ignoreSpaces(form.email.value);
<?
if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){
?>
        form.userPhone.value = ignoreSpaces(form.userPhone.value);
<?
}
?>
	if(form.newPasswd1.value != "") {
                 if (form.newPasswd2.value == null || form.newPasswd2.value == "" ) {
                        alert("���ο� ��й�ȣ Ȯ���� �Է����ּ���.");
                        form.newPasswd2.focus();
                        return;
                }
                if (form.newPasswd1.value != form.newPasswd2.value) {
                        alert("��й�ȣ�� ��й�ȣ Ȯ���� ��ġ���� �ʽ��ϴ�.");
                        form.newPasswd1.focus();
                        return;
                }
        }
<?
if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){
?>

        if (form.userPhone.value == null || form.userPhone.value == "") {
                alert("��ȭ��ȣ�� �Է��� �ּ���");
                form.userPhone.focus();
                return;
        }
<?
}
?>
        if (form.email.value == null || form.email.value == "") {
                alert("email�� �Է��� �ּ���");
                form.email.focus();
                return;
        }
	if(form.newPasswd1.value != ""){
                var newPasswd = form.newPasswd1.value;
                var encoded_newPasswd = hex_md5(newPasswd);
                form.newPasswd1.value = encoded_newPasswd;
        }
	if (form.userPasswd.value == null || form.userPasswd.value == "") {
                alert("���� �α��� ��й�ȣ�� �Է��ϼž� ������ �����մϴ�");
                form.userPasswd.focus();
                return;
        }
        var password = form.userPasswd.value;
        var encode_passwd = hex_md5(password);
        form.userPasswd.value = encode_passwd;
        form.submit();
}
</SCRIPT>
<FORM NAME="joinModi" METHOD="post" ACTION="joinModiProc.php">
<INPUT TYPE="HIDDEN" NAME="sno" VALUE="<?=trim($sno)?>">
<TABLE BORDER="0" cellpadding="0" cellspacing="0" width="590">
<tr>
	<td colspan="2"><img src="<?=IMG_URL?>site/examadmin/title_11.gif" border="0"></td>
</tr> 
<TR>
        <TD width="160" class="htitle" align="center">ID</TD>
        <TD width="430" class="htop"><?=$_SESSION['ID']?></TD>
</TR>
<TR>
        <TD class="htitle" align="center">PASSWORD</TD>
        <TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="userPasswd"></TD>
</TR>
<TR>
        <TD class="htitle" align="center">���ο� PASSWORD</TD>
        <TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="newPasswd1"><FONT COLOR="RED"> * PASSWORD ����ÿ��� �Է��� �ּ���</FONT></TD>
</TR>
<TR>
        <TD class="htitle" align="center">���ο� PASSWORD Ȯ��</TD>
        <TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="newPasswd2"><FONT COLOR="RED"> * PASSWORD ����ÿ��� �Է��� �ּ���</FONT></TD>
</TR>
<TR>
        <TD class="htitle" align="center">�̸�</TD>
        <TD class="hmiddle"><?=$_SESSION['Name']?></TD>
</TR>
<?
if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){
?>
<TR>
        <TD class="htitle" align="center">�й�</TD>
        <TD class="hmiddle"><?=trim($sno)?></TD>
</TR>
<TR>
        <TD class="htitle" align="center">�а�</TD>
        <TD class="hmiddle"><?=trim($dept)?></TD>
</TR>
<TR>
        <TD class="htitle" align="center">�г�</TD>
        <TD class="hmiddle"><?=trim($grade)?></TD>
</TR>
<TR>
        <TD class="htitle" align="center">��ȭ��ȣ</TD>
        <TD class="hmiddle">
                <INPUT TYPE="TEXT" NAME="userPhone" MAXLENGTH="14" SIZE="14" VALUE="<?=trim($tel)?>">
        </TD>
</TR>
<?
}
?>
<TR>
        <TD class="htitle" align="center">E-mail</TD>
        <TD class="hbottom"><INPUT TYPE="TEXT" NAME="email" VALUE="<?=trim($email)?>"></TD>
</TR>
<TR>
        <TD COLSPAN="2" ALIGN="RIGHT"><A HREF="javascript:check()"><img src="<?=IMG_URL?>site/icon/user_modi.gif"></A></TD>
</TR>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
