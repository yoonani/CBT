<?
/* =============================================================================
File : joinModi.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
        사용자 수정 폼
        학생들이 PASSWORD, 전화번호, E-mail  등을 변경
============================================================================= */
require_once("../include/conf.php");
require_once (MY_INCLUDE . "header.php");


//
// 직접 접근 제어
//
if (!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
        $fnc->alertBack("접근할 수 없습니다.");
        exit;
}

//
// SESSION LEVEL CHECK
//
if( !$fnc->checkLevel($_SESSION["Level"], array("A", "B", "D", "F")) ){
	$fnc->alertBack("접근할 수 없습니다.");
	exit;
}

//
// 사용자 정보 DB에서 추출
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
<?
if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){
?>

        if (form.userPhone.value == null || form.userPhone.value == "") {
                alert("전화번호를 입력해 주세요");
                form.userPhone.focus();
                return;
        }
<?
}
?>
        if (form.email.value == null || form.email.value == "") {
                alert("email을 입력해 주세요");
                form.email.focus();
                return;
        }
	if(form.newPasswd1.value != ""){
                var newPasswd = form.newPasswd1.value;
                var encoded_newPasswd = hex_md5(newPasswd);
                form.newPasswd1.value = encoded_newPasswd;
        }
	if (form.userPasswd.value == null || form.userPasswd.value == "") {
                alert("현재 로그인 비밀번호를 입력하셔야 수정이 가능합니다");
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
        <TD class="htitle" align="center">새로운 PASSWORD</TD>
        <TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="newPasswd1"><FONT COLOR="RED"> * PASSWORD 변경시에만 입력해 주세요</FONT></TD>
</TR>
<TR>
        <TD class="htitle" align="center">새로운 PASSWORD 확인</TD>
        <TD class="hmiddle"><INPUT TYPE="PASSWORD" NAME="newPasswd2"><FONT COLOR="RED"> * PASSWORD 변경시에만 입력해 주세요</FONT></TD>
</TR>
<TR>
        <TD class="htitle" align="center">이름</TD>
        <TD class="hmiddle"><?=$_SESSION['Name']?></TD>
</TR>
<?
if(!$fnc->checkLevel($_SESSION["Level"], array("A", "D", "F")) ){
?>
<TR>
        <TD class="htitle" align="center">학번</TD>
        <TD class="hmiddle"><?=trim($sno)?></TD>
</TR>
<TR>
        <TD class="htitle" align="center">학과</TD>
        <TD class="hmiddle"><?=trim($dept)?></TD>
</TR>
<TR>
        <TD class="htitle" align="center">학년</TD>
        <TD class="hmiddle"><?=trim($grade)?></TD>
</TR>
<TR>
        <TD class="htitle" align="center">전화번호</TD>
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
