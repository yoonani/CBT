<?
/* =============================================================================
File : join.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
        사용자 등록 폼
        학교별 관리자가 해당 학교의 학생의 학번, 학과, 학년, 주민번호 앞자리를
        입력 후 학생들이 ID, PASSWORD, 전화번호, E-mail 입력 후 회원가입하는
        폼
============================================================================= */
require_once("../include/conf.php");
require_once(MY_INCLUDE . "frmValid.php");


//
// 직접 접근 검사
//
if(!$fnc->isPOST()){
	$fnc->alertBack("접근할 수 없습니다.");
	exit;
}
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

//
// enterJoin.php에서 전달받은 값 확인
//
if($fv->lengthlt($_POST["univSNO"], 1)) {
        $fnc->alertBack("학번을 입력하세요");
        exit;
}
if($fv->lengthlt($_POST["univID"], 1)) {
        $fnc->alertBack("학교를 선택하세요");
        exit;
}
if($fv->lengthlt($_POST["studRNO1"], 6)) {
        $fnc->alertBack("주민등록번호 앞자리를 확인해 주세요");
        exit;
}


//
// UnivStudent Table 검사 쿼리
//
$sql = "select studName, studDept, studGrade from UnivStudent where univID = '".$_POST['univID']."' and univSNO = '".$_POST['univSNO']."' and studRNO1 = '".$_POST['studRNO1']."'";

$checkIDsql = "select myID from UserInfo where univID = '".$_POST['univID']."' and univSNO = '".$_POST['univSNO']."'";

if(!$DB->query($sql)){
	echo $DB->error();
	$fnc->alertBack("학생정보를 불러올 수 없습니다. 잠시후 다시 시도하세요");
}
if($cnt = $DB->noRows() <  1){
	$fnc->alertBack("학생 정보가 없습니다. 다시 입력하세요");
}
$myName = $DB->getResult(0,0);
$myDept = $DB->getResult(0,1);
$myGrad = $DB->getResult(0,2);

if(!$DB->query($checkIDsql)){
	echo $DB->error();
	$fnc->alertBack("학생정보를 불러올 수 없습니다. 잠시후 다시 시도하세요");
}
if($cnt = $DB->noRows()){
	$myID = $DB->getResult(0,0);
	$fnc->alertBack("이미 ID : ".trim($myID)." 로 등록되어 있습니다");
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
                alert("4~10자로 아이디(ID)를 입력해주세요. 영문자와 숫자만 가능합니다");
                form.userID.focus();
                return;
        }
        else {
                var ch;
                for (var i=0; i<form.userID.value.length; i++) {
                        ch = form.userID.value.charAt(i);
                        if ( !(ch >= 'a' && ch <= 'z') && !(ch >= '0' && ch <= '9') && !(ch >= 'A' && ch <= 'Z') ) {
                                alert("아이디(ID)를 입력해주세요. 영문자와 숫자만 가능합니다.");
                                form.userID.focus();
                                return;
                        }
                }
        }
	if (form.userPasswd1.value == null || form.userPasswd1.value == "" || form.userPasswd1.value.length < 4) {
                alert("4자 이상의 비밀번호를 입력해주세요.  ");
                form.userPasswd1.focus();
                return;
        }
        if (form.userPasswd2.value == null || form.userPasswd2.value == "" || form.userPasswd2.value.length < 4) {
                alert("비밀번호 확인을 입력해주세요.");
                form.userPasswd2.focus();
                return;
        }
        if (form.userPasswd1.value != form.userPasswd2.value) {
                alert("비밀번호와 비밀번호 확인이 일치하지 않습니다.");
                form.userPasswd1.focus();
                return;
        }
	if (form.userPhone.value == null || form.userPhone.value == "") {
                alert("전화번호를 입력해 주세요");
                form.userPhone.focus();
                return;
        }
	if (form.email.value == null || form.email.value == "") {
                alert("email을 입력해 주세요");
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
			alert("ID를 입력하고, ID 중복확인을 해주세요");
		}else{
			alert("ID 중복확인을 해주세요");
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
                        <TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu2"><A HREF="<?=URL_ROOT?>enterJoin.php">사용자등록</a>&nbsp;&nbsp;&nbsp;</TD>
                </TR>
                <TR>
                        <TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu2"><A HREF="<?=URL_ROOT?>find.php">아이디/패스워드찾기</a>&nbsp;&nbsp;&nbsp;</TD>
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
	<TD width="440" class="htop" align="absmiddle"><INPUT TYPE="TEXT" NAME="userID"><?$fnc->imgButton(66, 18, "javascript:checkID()", IMG_URL . "site/icon/id_check.gif");?></A><FONT COLOR="RED"> * 4자에서 10자로 등록 후 변경 불가</FONT></TD>
</TR>
<TR>
	<TD class="htitle" align="center">PASSWORD</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="userPasswd1" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">PASSWORD 확인</TD>
	<TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="userPasswd2" OnClick="isID()" READONLY></TD>
</TR>
<TR>
	<TD class="htitle" align="center">이름</TD>
	<TD class="hmiddle"><?=trim($myName)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">학번</TD>
	<TD class="hmiddle"><?=trim($_POST['univSNO'])?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">학과</TD>
	<TD class="hmiddle"><?=trim($myDept)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">학년</TD>
	<TD class="hmiddle"><?=trim($myGrad)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">전화번호</TD>
	<TD class="hmiddle">
		<INPUT TYPE="TEXT" NAME="userPhone" MAXLENGTH="14" SIZE="14" OnClick="isID()" READONLY>
	</TD>
</TR>
<TR>
	<TD class="htitle" align="center">E-mail</TD>
	<TD class="hbottom"><FONT COLOR="RED"><INPUT TYPE="TEXT" NAME="email" SIZE="14" OnClick="isID()" READONLY> * 패스워드분실 시 발송될 Email 주소(학교 메일 권장)</FONT></TD>
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
