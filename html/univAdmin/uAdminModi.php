<?
/*=============================================================================
File : univAdmin/uAdminModi.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.10 
================================================================================
Desc.
        학교 관리자 수정 페이지
	eachUniv/index.php에서 해당 학교 관리자 수정을 클릭하였을때 페이지
        전체 관리자(A) 접근 가능
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");

//
// 직접 접근 제어
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

//
// 권한 제어 $_SESSION['Level'] 이 'A' 만 접근할 수 있다
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
        $fnc->alertBack("접근할 수 없는 권한입니다.");
        exit;
}

//
// eachUniv/index.php에서 넘어온 값 확인
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("학교정보를 받아올 수 없습니다");
        exit;
}
if($fv->lengthlt(trim($_POST["myID"]), 1)) {
        $fnc->alertBack("학교정보를 받아올 수 없습니다");
        exit;
}

$sql = "select myID, myName, myRNO1, myEmail from staffinfo where myID = '".$_POST['myID']."' and myScode = '".$_POST['univID']."'";
if(!$DB->query($sql)){
        echo $DB->error();
        exit;
}
$myID = $DB->getResult(0,0);
$myName = $DB->getResult(0,1);
$myRNO = $DB->getResult(0,2);
$myEmail = $DB->getResult(0,3);
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
        var form = document.adminModi;
        form.id.value = ignoreSpaces(form.id.value);
        form.name.value = ignoreSpaces(form.name.value);
        form.newPasswd1.value = ignoreSpaces(form.newPasswd1.value);
        form.newPasswd2.value = ignoreSpaces(form.newPasswd2.value);
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
        if(form.newPasswd1.value != "") {
                 if (form.newPasswd2.value == null || form.newPasswd2.value == "" ) {
                        alert("새로운 비밀번호 확인을 입력해주세요.");
                        form.newPasswd2.focus();
                        return;
                }
                if (form.newPasswd1.value != form.newPasswd2.value) {
                        alert("비밀번호와 비밀번호 확인이 일치하지 않습니다.");
                        form.newPasswd1.focus();
                        return;
                }
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
        if(form.newPasswd1.value != ""){
                var newPasswd = form.newPasswd1.value;
                var encoded_newPasswd = hex_md5(newPasswd);
                form.newPasswd1.value = encoded_newPasswd;
        }
	form.submit();
}
function checkID() {
        var form = document.adminModi;
        window.open("checkID.php?userID="+form.id.value,"checkID","height=200,width=300 resizable=no");

}
</SCRIPT>
<FORM NAME="adminModi" METHOD="POST" ACTION="uAdminModiProc.php">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="<?=$_POST['univID']?>">
<INPUT TYPE="HIDDEN" NAME="myID" VALUE="<?=$_POST['myID']?>">
<TABLE BORDER="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2"><img src="<?=IMG_URL?>site/examadmin/title_15_2.gif"></td>
</tr>
<TR>
        <TD width="150" class="htitle" align="center">ID</TD>
        <TD width="440" class="htop"><INPUT TYPE="TEXT" NAME="id" VALUE="<?=$myID?>"><?$fnc->imgButton(66, 18, "javascript:checkID()", IMG_URL . "site/icon/id_check.gif");?></A></TD>
</TR>
<TR>
        <TD width="150" class="htitle" align="center">이름</TD>
        <TD class="hmiddle"><INPUT TYPE="TEXT" NAME="name" VALUE="<?=$myName?>"></TD>
</TR>
<TR>
        <TD width="150" class="htitle" align="center">새로운 PASSWORD</TD>
        <TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="newPasswd1"><FONT COLOR="RED"> * PASSWORD 변경시에만 입력해 주세요</FONT></TD>
</TR>
<TR>
        <TD width="150" class="htitle" align="center">새로운 PASSWORD확인</TD>
        <TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="newPasswd2"><FONT COLOR="RED"> * PASSWORD 변경시에만 입력해 주세요</FONT></TD>
</TR>
<TR>
        <TD width="150" class="htitle" align="center">주민번호 앞자리</TD>
        <TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="rno" VALUE="<?=$myRNO?>"></TD>
</TR>
<TR>
        <TD width="150" class="htitle" align="center">E-mail</TD>
        <TD class="hbottom"><INPUT TYPE="TEXT" NAME="email" VALUE="<?=$myEmail?>"></TD>
</TR>
<TR>
        <TD COLSPAN="2" ALIGN="RIGHT"><A HREF="javascript:check();"><img src="<?=IMG_URL?>site/icon/submit.gif"></A><A HREF="javascript:history.go(-1)"><img src="<?=IMG_URL?>site/icon/cancel.gif"></A></TD>
</TR>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
