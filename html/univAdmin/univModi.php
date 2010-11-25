<?
/* =============================================================================
File : univAdmin/univModi.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7.10 
================================================================================
Desc.
        학교 등록 페이지
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
if(!$fnc->isPOST()){
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

//
// univAdmin/index.php에서 넘어온 값 확인
//
if($fv->lengthlt(trim($_POST["univID"]), 1)) {
        $fnc->alertBack("학교정보를 받아올 수 없습니다");
        exit;
}

$sql = "select myCode, myTitle from univInfo where myCode = '".trim($_POST["univID"])."'";
if(!$DB->query($sql)){
        $fnc->alertBack("학교정보를 받아올 수 없습니다");
        exit;
}

$myCode = $DB->getResult(0,0);
$myTitle = $DB->getResult(0,1);
require_once (MY_INCLUDE . "header.php");
?>
<FORM NAME="univModi" METHOD="POST" ACTION="univModiProc.php">
<INPUT TYPE="HIDDEN" NAME="univID" VALUE="<?=trim($myCode)?>">
<TABLE BORDER="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2">타이틀 들어가는 부분</td>
</tr>
<TR>
	<TD width="150" class="htitle" align="center">학교코드</TD>
	<TD width="440" class="htop"><?=trim($myCode)?></TD>
</TR>
<TR>
	<TD class="htitle" align="center">학교명</TD>
	<TD class="hbottom"><INPUT TYPE="TEXT" NAME="univTitle" SIZE="10" VALUE="<?=trim($myTitle)?>"></TD>
</TR>
<TR>
	<TD COLSPAN="2" ALIGN="RIGHT"><A hREF="javascript:document.univModi.submit()"><img src="<?=IMG_URL?>site/icon/submit.gif"></A><A HREF="javascript:history.go(-1)"><img src="<?=IMG_URL?>site/icon/cancel.gif"></A></TD>
</TR>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
