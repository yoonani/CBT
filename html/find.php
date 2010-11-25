<?
/* =============================================================================
File : find.php
================================================================================
Coded by BIO(iypark@hallym.ac.kr)
================================================================================
Date : 2006. 7. 06
================================================================================
Desc.
	첫 페이지에서 'ID, PASSWORD 찾기' 를 클릭했을 때 POPUP 으로 뜨는 페이지
	학교와 학번을 입력 후 findProc.php로 POST방식으로 변수값 전달
============================================================================= */
require_once("../include/conf.php");
?>
<HTML>
<HEAD>
        <TITLE>[Welcome]</TITLE>
        <LINK REL="stylesheet" TYPE="text/css" HREF="<?=URL_ROOT?>include/css/medcbt.css">
	<SCRIPT LANGUAGE="JavaScript">
		function FindOpen(){
			var form = document.findID;
			form.target = "FindID";
			window.open('','FindID','height=200,width=300 resizable=no');
			form.submit();
		}
	</SCRIPT>
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
                        <TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu2"><A HREF="<?=URL_ROOT?>enterJoin.php">사용자등록</A>&nbsp;&nbsp;&nbsp;</TD>
                </TR>
                <TR>
                        <TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu2"><A HREF="<?=URL_ROOT?>find.php">아이디/패스워드찾기</A>&nbsp;&nbsp;&nbsp;</TD>
                </TR>
<!-- Menu Part End -->
                </TABLE>
        </TD>
<!-- Body Part -->
        <TD WIDTH="650" VALIGN="TOP" BACKGROUND="<?=IMG_URL?>site/skin/main_bg.gif" class="main">

<FORM NAME="findID" ACTION="<?=URL_ROOT?>findProc.php" METHOD="POST">
<table border="0" width="590" cellpadding="0" cellspacing="0">
<tr>
	<td><img src="<?=IMG_URL?>site/userjoin/id_pw.gif"></td>
</tr>
<tr>
	<td width="590" height="200" align="center" background="<?=IMG_URL?>site/userjoin/bg.gif">
	<table border="0" width="390" cellpadding="0" cellspacing="0">
		<tr height="25">
			<td width="100" align="center" class="htitle">학교</td>
			<td width="290" class="htop">
			<SELECT NAME="univ" SIZE="1">
				<OPTION VALUE="HL">한림대학교</OPTION>
			</SELECT>
			</td>
		</tr>
		<tr height="25">
			<td align="center" class="htitle">학번</td> 
			<td class="hbottom"><INPUT TYPE="TEXT" NAME="sno" SIZE="10" onkeydown="if(event.keyCode == 13) FindOpen();"></td>	
		</tr>
		<tr height="25">
			<td colspan="2" align="right">
				<A HREF="javascript:FindOpen();"><img src="<?=IMG_URL?>site/icon/user_find.gif"></A>
				<A HREF="<?=URL_ROOT?>"><img src="<?=IMG_URL?>site/icon/cancel.gif"></A>
			</td>
		</tr>
	</table>
	</td>
</tr>
</table>
</FORM>
	</td>
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

<?
ob_end_flush();
?>
