<?
/* =============================================================================
File : univAdmin/addUniv.php
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

//
// 직접 접근 제어
//
if(!eregi("^".URL_ROOT, $_SERVER[HTTP_REFERER])) {
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
?>
<FORM NAME="addUniv" METHOD="POST" ACTION="univAddProc.php">
<TABLE BORDER=0 width="590" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="3"><img src="<?=IMG_URL?>site/examadmin/title_14_4.gif"></td>
</tr>
<TR>
	<TD width="150" class="htitle" align="center">학교코드</TD>
	<TD width="50" class="htop"><INPUT TYPE="TEXT" NAME="univID" SIZE="2" MAXLENGTH="2"></TD><TD WIDTH="380" class="htop"><FONT COLOR="RED"> * 변경이 불가능하오니 신중하게 입력하여 주세요<BR> * 영문 두글자까지 가능합니다. ex) 학교 코드 : HL</FONT></TD>
</TR>
<TR>
	<TD class="htitle" align="center">학교명</TD>
	<TD class="hbottom"><INPUT TYPE="TEXT" NAME="univTitle" SIZE="10"></TD><TD class="hbottom"><FONT COLOR="RED"> * ex) 한림대학교</FONT></TD>
</TR>
<TR>
	<TD COLSPAN="3" ALIGN="RIGHT"><A hREF="javascript:document.addUniv.submit()"><img src="<?=IMG_URL?>site/icon/submit.gif"></A><A HREF="javascript:history.go(-1)"><img src="<?=IMG_URL?>site/icon/cancel.gif"></A></TD>
</TR>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
