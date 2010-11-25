<?php
/* =============================================================================
File : setup2.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Desc 
	- ��ġ ���� 2�ܰ�
	- DB ����
	- ������ ��ȣ ����
============================================================================= */
?>
<HTML>
<HEAD>
	<TITLE>[Install - Step#2]</TITLE>
</HEAD>
<BODY>
<?
// �⺻ ���丮 ����
$cdir = realpath("./");
$baseDir = substr($cdir, 0, strrpos($cdir, "/"));
define("BASE_DIR", substr($cdir, 0, strrpos($cdir, "/")) );

?>
<SCRIPT SRC="./include/md5.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
	function procStep2() {
		var myFrm = document.getDBInfo;
		var myHost = trim(myFrm.getHost.value);
		var myPort = trim(myFrm.getPort.value);
		var myUser = trim(myFrm.getUser.value);
		var myPassword = trim(myFrm.getPassword.value);
		var myDBName = trim(myFrm.getDBName.value);
		var myAID = trim(myFrm.getAID.value);
		var myAPWD = trim(myFrm.getAPWD.value);
		var myAREPWD = trim(myFrm.getAREPWD.value);
		var myEMail = trim(myFrm.getEMail.value);
		var mySiteName = trim(myFrm.getSiteName.value);

		if(myHost.length < 1) {
			alert('DB Host�� �Է��ϼ���');
			myFrm.getHost.focus();	
			return false;
		}
		if(myPort.length < 1) {
			alert('DB Port�� �Է��ϼ���');
			myFrm.getPort.focus();	
			return false;
		}
		if(myUser.length < 1) {
			alert('DB ����� ID�� �Է��ϼ���');
			myFrm.getUser.focus();	
			return false;
		}
		if(myPassword.length < 1) {
			alert('DB ��ȣ�� �Է��ϼ���');
			myFrm.getPassword.focus();	
			return false;
		}
		if(myDBName.length < 1) {
			alert('DB ���� �Է��ϼ���');
			myFrm.getDBName.focus();	
			return false;
		}
		if(myAID.length < 1) {
			alert('����Ʈ ��ü������ ID�� �Է��ϼ���');
			myFrm.getAID.focus();	
			return false;
		}
		if(myAPWD.length < 1) {
			alert('����Ʈ ��ü������ ��ȣ�� �Է��ϼ���');
			myFrm.getAPWD.focus();	
			return false;
		}
		if(myAPWD.length < 5 || myAPWD.length > 12) {
			alert('����Ʈ ��ü������ ��ȣ�� �ּ� 5�ں��� �ִ� 12�ڱ��� �Դϴ�.');
			myFrm.getAPWD.value = '';
			myFrm.getAPWD.focus();	
			return false;
		}

		if(myAREPWD.length < 1) {
			alert('����Ʈ ��ü������ ��ȣ�� �ѹ� �� �Է��ϼ���');
			myFrm.getAREPWD.focus();	
			return false;
		}

		if(myAPWD != myAREPWD) {
			alert('����Ʈ ��ü������ ��ȣ�� ��ġ���� �ʽ��ϴ�.\n�ٽ� �ѹ� �Է��ϼ���');
			myFrm.getAPWD.value = '';
			myFrm.getAREPWD.value = '';
			myFrm.getAPWD.focus();	
			return false;
		}
		if(myEMail.length < 1) {
			alert('����Ʈ ��ü������ E-mail�� �Է��ϼ���');
			myFrm.getEMail.focus();	
			return false;
		}
		if(mySiteName.length < 1) {
			alert('����Ʈ �̸��� �Է��ϼ���');
			myFrm.getSiteName.focus();	
			return false;
		}
//		alert(hex_md5('test123'));
		myFrm.getAPWD.value=hex_md5(myAPWD);
		myFrm.getAREPWD.value=hex_md5(myAPWD);

		// Form ������ �̷�����.
		myFrm.submit();
		return true;
	}

	function trim(s) {
		s += ''; // ���ڶ� ���ڿ��� ��ȯ
		return s.replace(/^\s*|\s*$/g, '');
	}
</SCRIPT>
<H3 ALIGN="CENTER">DB�� ������ ��ȣ ����</H3>
<TABLE WIDTH="400" ALIGN="CENTER" style="font-size: 12px;">
<FORM NAME="getDBInfo" METHOD="POST" ACTION="setup2Proc.php">
<TR>
	<TD COLSPAN="2" style="border-width:1; border-color:rgb(0,102,255); border-style:dotted;" WIDTH="500" HEIGHT="25">DB ����</TD>
</TR>
<TR>
	<TD WIDTH="100">Host</TD>
	<TD WIDTH="300"><INPUT TYPE="TEXT" NAME="getHost" SIZE="20" MAXLENGTH="255" VALUE="localhost"></TD>
</TR>
<TR>
	<TD WIDTH="100">Port</TD>
	<TD WIDTH="300"><INPUT TYPE="TEXT" NAME="getPort" SIZE="20" MAXLENGTH="255" VALUE="5432"></TD>
</TR>
<TR>
	<TD>User</TD>
	<TD><INPUT TYPE="TEXT" NAME="getUser" SIZE="20" MAXLENGTH="255"></TD>
</TR>
<TR>
	<TD>Password</TD>
	<TD><INPUT TYPE="TEXT" NAME="getPassword" SIZE="20" MAXLENGTH="255"></TD>
</TR>
<TR>
	<TD>DB Name</TD>
	<TD><INPUT TYPE="TEXT" NAME="getDBName" SIZE="20" MAXLENGTH="255"></TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
	<TD>&nbsp;</TD>
</TR>
<TR>
	<TD COLSPAN="2" style="border-width:1; border-color:rgb(0,102,255); border-style:dotted;" HEIGHT="25">������ ����</TD>
</TR>
<TR>
	<TD>������ID</TD>
	<TD><INPUT TYPE="TEXT" NAME="getAID" SIZE="20" MAXLENGTH="255" VALUE="admin"></TD>
</TR>
<TR>
	<TD>��ȣ</TD>
	<TD><INPUT TYPE="PASSWORD" NAME="getAPWD" SIZE="20" MAXLENGTH="255"><BR />�� �ּ� 5�ڿ��� 12�� ���ϱ��� �����մϴ�.</TD>
</TR>
<TR>
	<TD>��ȣ ���Է�</TD>
	<TD><INPUT TYPE="PASSWORD" NAME="getAREPWD" SIZE="20" MAXLENGTH="255"></TD>
</TR>
<TR>
	<TD>������ e-mail</TD>
	<TD><INPUT TYPE="TEXT" NAME="getEMail" SIZE="30" MAXLENGTH="255"></TD>
</TR>
<TR>
	<TD>Site �̸�</TD>
	<TD><INPUT TYPE="TEXT" NAME="getSiteName" SIZE="30" MAXLENGTH="255"></TD>
</FORM>
</TR>
</TABLE>
<TABLE WIDTH="400" ALIGN="CENTER"  style="font-size: 12px;">
<TR>
	<TD ALIGN="CENTER">[<A HREF="#" OnClick="procStep2();">��������</A>]&nbsp;[<A HREF="#">���</A>]</TD>
</TR>
</TABLE>
</BODY>
</HTML>
