<?
/* =============================================================================
File : findProc.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
        find.php�κ���  POST������� �б�ID(univ)�� �й�(sno)�� ���޹޾� �ش� 
	�б��� �й��� �ش��ϴ� ID�� ã�� Process ����
	ID �� �������� ���� ��� ��� �� �ٽ� �ڷ� ����
============================================================================= */
require_once("../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// ���� ���� �˻�
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("������ �� �����ϴ�.");
	self.close();
</SCRIPT>
<?
}

if(!$fnc->isPOST()){
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("������ �� �����ϴ�.");
        self.close();
</SCRIPT>
<?
}
//
// window.name �˻�
//
?>
<SCRIPT LANGUAGE="JavaScript">
	if(window.name != 'FindID'){
		alert("������ �� �����ϴ�.");
		self.close();
	}
</SCRIPT>
<?
//
// find.php���� ���޹��� �б�(univ)�� �й�(sno)�� ���� �ùٸ��� �˻�
//
if($fv->lengthlt($_POST["sno"], 1)) {
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("�й��� �Է��ϼ���");
        self.close();
</SCRIPT>
<?
}
if($fv->lengthlt($_POST["univ"], 1)) {
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("�б��� �����ϼ���");
        self.close();
</SCRIPT>
<?
}

//
// �ش� �б�ID�� �л��� �й��� matching �Ǵ� �л� ID �� DB���� �����ϴ� ���� -------> 1��
//
$sql = "select u.myID, us.studeemail, us.studName from userinfo u join univstudent us on us.univID = u.univid and us.univSNO = u.univSNO where u.univid = '".$_POST['univ']."' and u.univsno = '".$_POST['sno']."'";

//
// ����� 1�� ���� DB ����
//
if(!$DB->query($sql)){
?>
<SCRIPT LANGUAGE="JavaScript">
	alert("�б��� �й� ������ �ҷ��� �� �����ϴ� ����Ŀ� �ٽ� �õ��� �ּ���");
        self.close();
</SCRIPT>
<?
}

//
// ������ ����� 0���� ��(ID�� �������� ����)
//
if($row = $DB->noRows() < 1) {
?>
<SCRIPT LANGUAGE="JavaScript">
        alert("�������� �ʴ� ���̵��Դϴ�. ȸ������ �� ����ϼ���");
        self.close();
</SCRIPT>
<?
}

//
// �ش� �б�ID�� �л��� �й��� matching �Ǵ� �л� ID�� e-mail�� ���� �޾ƿ� -------> 2��
//
$myID = $DB->getResult(0,0);
$myEmail = $DB->getResult(0,1);
$myName = $DB->getResult(0,2);

//
// PASSWORD�� ã�� ��ư�� Ŭ���ϸ� ���ο� PASSWORD �� �ش� �л��� ���Ϸ� ������ ������ �ϴ� ���â�� ���
//
?>
<script language="JavaScript">
function passwdFind() {
	document.sendPasswd.submit();
}
</script>
<LINK REL="stylesheet" TYPE="text/css" HREF="<?=URL_ROOT?>include/css/medcbt.css">
</head>
<!-- ID ȭ�� ��� -->
<body topmargin="0" leftmargin="0">
<form name="sendPasswd" method="POST" action="mail.php">
<input type="hidden" name="id" value="<?=trim($myID)?>">
<input type="hidden" name="mail" value="<?=trim($myEmail)?>">
<input type="hidden" name="name" value="<?=trim($myName)?>">
</form>
<table border="0" width="300" height="200" cellpadding="0" cellspacing="0">
<tr>
	<td height="80"><img src="<?=IMG_URL?>site/userjoin/pw_title.gif"></td>
</tr>
</tr>
	<td height="80" align="center" background="<?=IMG_URL?>site/userjoin/pw_bg1.gif">
		<table border="0" width="300" height="80" cellpadding="0" cellspacing="0">
			<tr>
				<td align="center" height="20" style="color:#A82923">���̵�� '<b><?=$myID?></b>' �Դϴ�.</td>
			</tr>
			<tr>
				<td align="center" height="20" style="color:#A82923">�н������ E-MAIL�� �߼۵˴ϴ�.</td>
			</tr>
			<tr>
				<td align="center" height="20" style="color:#A82923;font:11pt ����;"><b><?=$myEmail?></b></td>
			</tr>
			<tr>
				<td align="center" height="20" style="color:#A82923">�ּҰ� ������ ������ ��ư�� �����ּ���.</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td height="40" align="right" background="<?=IMG_URL?>site/userjoin/pw_bg2.gif"><a href="javascript:passwdFind()"><img src="<?=IMG_URL?>site/icon/pw_send.gif"></a>  <a href="javascript:window.close()"><img src="<?=IMG_URL?>site/icon/pw_cancel.gif"></a>&nbsp;&nbsp;</td>
</tr>
</table>
</body>
<?
ob_end_flush();
?>

