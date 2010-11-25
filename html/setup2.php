<?php
/* =============================================================================
File : setup2.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Desc 
	- 설치 파일 2단계
	- DB 설정
	- 관리자 암호 설정
============================================================================= */
?>
<HTML>
<HEAD>
	<TITLE>[Install - Step#2]</TITLE>
</HEAD>
<BODY>
<?
// 기본 디렉토리 설정
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
			alert('DB Host를 입력하세요');
			myFrm.getHost.focus();	
			return false;
		}
		if(myPort.length < 1) {
			alert('DB Port를 입력하세요');
			myFrm.getPort.focus();	
			return false;
		}
		if(myUser.length < 1) {
			alert('DB 사용자 ID를 입력하세요');
			myFrm.getUser.focus();	
			return false;
		}
		if(myPassword.length < 1) {
			alert('DB 암호를 입력하세요');
			myFrm.getPassword.focus();	
			return false;
		}
		if(myDBName.length < 1) {
			alert('DB 명을 입력하세요');
			myFrm.getDBName.focus();	
			return false;
		}
		if(myAID.length < 1) {
			alert('사이트 전체관리자 ID를 입력하세요');
			myFrm.getAID.focus();	
			return false;
		}
		if(myAPWD.length < 1) {
			alert('사이트 전체관리자 암호를 입력하세요');
			myFrm.getAPWD.focus();	
			return false;
		}
		if(myAPWD.length < 5 || myAPWD.length > 12) {
			alert('사이트 전체관리자 암호는 최소 5자부터 최대 12자까지 입니다.');
			myFrm.getAPWD.value = '';
			myFrm.getAPWD.focus();	
			return false;
		}

		if(myAREPWD.length < 1) {
			alert('사이트 전체관리자 암호를 한번 더 입력하세요');
			myFrm.getAREPWD.focus();	
			return false;
		}

		if(myAPWD != myAREPWD) {
			alert('사이트 전체관리자 암호가 일치하지 않습니다.\n다시 한번 입력하세요');
			myFrm.getAPWD.value = '';
			myFrm.getAREPWD.value = '';
			myFrm.getAPWD.focus();	
			return false;
		}
		if(myEMail.length < 1) {
			alert('사이트 전체관리자 E-mail을 입력하세요');
			myFrm.getEMail.focus();	
			return false;
		}
		if(mySiteName.length < 1) {
			alert('사이트 이름을 입력하세요');
			myFrm.getSiteName.focus();	
			return false;
		}
//		alert(hex_md5('test123'));
		myFrm.getAPWD.value=hex_md5(myAPWD);
		myFrm.getAREPWD.value=hex_md5(myAPWD);

		// Form 전송이 이뤄진다.
		myFrm.submit();
		return true;
	}

	function trim(s) {
		s += ''; // 숫자라도 문자열로 변환
		return s.replace(/^\s*|\s*$/g, '');
	}
</SCRIPT>
<H3 ALIGN="CENTER">DB와 관리자 암호 설정</H3>
<TABLE WIDTH="400" ALIGN="CENTER" style="font-size: 12px;">
<FORM NAME="getDBInfo" METHOD="POST" ACTION="setup2Proc.php">
<TR>
	<TD COLSPAN="2" style="border-width:1; border-color:rgb(0,102,255); border-style:dotted;" WIDTH="500" HEIGHT="25">DB 설정</TD>
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
	<TD COLSPAN="2" style="border-width:1; border-color:rgb(0,102,255); border-style:dotted;" HEIGHT="25">관리자 설정</TD>
</TR>
<TR>
	<TD>관리자ID</TD>
	<TD><INPUT TYPE="TEXT" NAME="getAID" SIZE="20" MAXLENGTH="255" VALUE="admin"></TD>
</TR>
<TR>
	<TD>암호</TD>
	<TD><INPUT TYPE="PASSWORD" NAME="getAPWD" SIZE="20" MAXLENGTH="255"><BR />※ 최소 5자에서 12자 이하까지 가능합니다.</TD>
</TR>
<TR>
	<TD>암호 재입력</TD>
	<TD><INPUT TYPE="PASSWORD" NAME="getAREPWD" SIZE="20" MAXLENGTH="255"></TD>
</TR>
<TR>
	<TD>관리자 e-mail</TD>
	<TD><INPUT TYPE="TEXT" NAME="getEMail" SIZE="30" MAXLENGTH="255"></TD>
</TR>
<TR>
	<TD>Site 이름</TD>
	<TD><INPUT TYPE="TEXT" NAME="getSiteName" SIZE="30" MAXLENGTH="255"></TD>
</FORM>
</TR>
</TABLE>
<TABLE WIDTH="400" ALIGN="CENTER"  style="font-size: 12px;">
<TR>
	<TD ALIGN="CENTER">[<A HREF="#" OnClick="procStep2();">다음으로</A>]&nbsp;[<A HREF="#">취소</A>]</TD>
</TR>
</TABLE>
</BODY>
</HTML>
