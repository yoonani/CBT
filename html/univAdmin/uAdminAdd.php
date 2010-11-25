<?
/*==============================================================================
File : univAdmin/uAdminAdd.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.11 
================================================================================
Desc.
	학교별 관리자 추가 페이지
	전체 관리자가 학교별 관리에 학교를 선택하여 접근하면, 학교 관리자가 
	등록되어 있지 않은 경우 전체 관리자가 학교 관리자를 등록시킬 수 있는
	페이지이다.
	GET방식으로 학교 ID를 전달받는다
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");


//
// 직접 접근 검사
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}
//
// 권한 제어 $_SESSION['Level'] 이 'A' or 'F' 만 접근할 수 있다
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("접근할 수 없는 권한입니다.");
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
                alert("4~12자로 아이디(ID)를 입력해주세요. 영문자와 숫자만 가능합니다");
                form.id.focus();
                return;
        }
        else {
                var ch;
                for (var i=0; i<form.id.value.length; i++) {
                        ch = form.id.value.charAt(i);
                        if ( !(ch >= 'a' && ch <= 'z') && !(ch >= '0' && ch <= '9') && !(ch >= 'A' && ch <= 'Z')  ) {
                                alert("아이디(ID)를 입력해주세요. 영문자와 숫자만 가능합니다.");
                                form.id.focus();
                                return;
                        }
                }
        }
        if (form.passwd1.value == null || form.passwd1.value == "" || form.passwd1.value.length < 4) {
                alert("4자 이상의 비밀번호를 입력해주세요.  ");
                form.passwd1.focus();
                return;
        }
        if (form.passwd2.value == null || form.passwd2.value == "" || form.passwd2.value.length < 4 ) {
                alert("비밀번호 확인을 입력해주세요.");
                form.passwd2.focus();
                return;
        }
        if (form.passwd1.value != form.passwd2.value) {
                alert("비밀번호와 비밀번호 확인이 일치하지 않습니다.");
                form.passwd1.focus();
                return;
        }
        if (form.email.value == null || form.email.value == "") {
                alert("email을 입력해 주세요");
                form.email.focus();
                return;
        }
        if (form.rno.value == null || form.rno.value == "" || form.rno.value.length != 6) {
                alert("주민등록번호 앞자리를 입력해주세요.");
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
                        alert("ID를 입력하고, ID 중복확인을 해주세요");
                }else{
                        alert("ID 중복확인을 해주세요");
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
	<TD class="htitle" align="center">이름</TD>
	<TD class="hmiddle"><INPUT TYPE="TEXT" NAME="name" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">PASSWORD</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="passwd1" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">PASSWORD확인</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="passwd2" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">주민번호 앞자리</TD>
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
