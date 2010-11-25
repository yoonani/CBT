<?php
require_once("../include/conf.php");
$myKeyWord = md5("LogInProc");
//echo $myKeyWord;
$fnc->useMD5();
$myURL = substr(URL_ROOT, 7);
?>
<HTML>
<HEAD>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<SCRIPT LANGUAGE="JavaScript">
function frmSubmit() {
//	alert(document.login.getPWD.value);
	document.login.getPWD.value=hex_md5(document.login.getPWD.value);
//	alert(document.login.getPWD.value);

	document.login.submit();
	return false;
}
</SCRIPT>
<TITLE><?=TITLE?></TITLE>
</HEAD>
<BODY BGCOLOR="WHITE" TEXT="BLACK" LINK="BLUE" VLINK="PURPLE" ALINK="RED" TOPMARGIN="0" LEFTMARGIN="0" <? if(!$_SESSION) echo "onLoad=\"document.login.getID.focus();\"";?>>
<TABLE WIDTH="800" HEIGHT="600" cellpadding="0" cellspacing="0" border="0">
<TR>
	<TD WIDTH="450"><IMG SRC="<?=IMG_URL?>site/main/main_left.gif" WIDTH="450" HEIGHT="550" BORDER="0"></TD>
	<TD WIDTH="350">
		<TABLE BORDER="0" WIDTH="350" HEIGHT="550" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD WIDTH="350" HEIGHT="200"><IMG SRC="<?=IMG_URL?>site/main/right_top.gif" WIDTH="350" HEIGHT="200" BORDER="0"></TD>
		</TR>
		<TR>
			<TD WIDTH="350" HEIGHT="100" BACKGROUND="<?=IMG_URL?>site/main/login_bg.gif">
<?
if(!$_SESSION['ID']){
?>
				<TABLE BORDER="0" WIDTH="350" HEIGHT="100" CELLPADDING="0" CELLSPACING="0">
				<TR>
					<TD WIDTH="340" HEIGHT="30">&nbsp;</TD>
				</TR>
				<!-- Login Part -->
				<FORM NAME="login" ACTION="http://<?=$myURL?>loginproc.php" METHOD="POST">
					<INPUT TYPE="HIDDEN" NAME="isLogInProc" VALUE="yes">
				<TR>
					<TD WIDTH="340" HEIGHT="40" valign="MIDDLE">
						<P ALIGN="CENTER">
						<IMG ALIGN="absmiddle" SRC="<?=IMG_URL?>site/main/id_button.gif" WIDTH="18" HEIGHT="15" BORDER="0">&nbsp;
						<INPUT TYPE="TEXT" NAME="getID" SIZE="11" MAXLENGTH="15">&nbsp;
						<IMG ALIGN="absmiddle" SRC="<?=IMG_URL?>site/main/pw_button.gif" WIDTH="24" HEIGHT="15" BORDER="0">&nbsp;
						<INPUT TYPE="PASSWORD" NAME="getPWD" SIZE="12" MAXLENGTH="15" onkeydown="if(event.keyCode == 13) frmSubmit();">&nbsp;
						<A OnClick="frmSubmit();" HREF="#"><IMG ALIGN="absmiddle" SRC="<?=IMG_URL?>site/main/login_button.gif" WIDTH="41" HEIGHT="39" BORDER="0"></A>
						</P>
					</TD>
				</TR>
				</FORM>
				<!-- Login Part End -->
				<TR>
					<TD WIDTH="340" HEIGHT="30">
						<P ALIGN="RIGHT">
						<A HREF="<?=URL_ROOT?>enterJoin.php"><IMG align="absmiddle" SRC="<?=IMG_URL?>site/main/user_join.gif" BORDER="0"></A><A HREF="<?=URL_ROOT?>find.php"><IMG align="absmiddle" SRC="<?=IMG_URL?>site/main/findid_button.gif" BORDER="0"></a>&nbsp;&nbsp;&nbsp;
						</P>
					</TD>
				</TR>
				</TABLE>
<?
}else{
?>
                                <TABLE BORDER="0" WIDTH="350" HEIGHT="100" CELLPADDING="0" CELLSPACING="0">
                                <TR>
                                        <TD WIDTH="340" HEIGHT="30">&nbsp;</TD>
                                </TR>
                                <TR>
                                        <TD WIDTH="340" HEIGHT="40" valign="MIDDLE">
                                                <P ALIGN="CENTER">'<?=$_SESSION['Name']?>'님이 로그인을 하셨습니다.</P>
                                        </TD>
                                </TR>
                                <TR>
                                        <TD WIDTH="340" HEIGHT="30">
                                                <P ALIGN="RIGHT">
							<A HREF="<?=URL_ROOT?>home/"><IMG SRC="<?=IMG_URL?>site/icon/home.gif" BORDER="0"></A>
							<A HREF="<?=URL_ROOT?>logout.php"><IMG SRC="<?=IMG_URL?>site/skin/logout_button.gif" BORDER="0"></A>
						</P>
                                        </TD>
                                </TR>
                                </TABLE>

<?
}
?>
			</TD>
		</TR>
		<TR>
			<TD WIDTH="350" HEIGHT="250" BACKGROUND="<?=IMG_URL?>site/main/exam_plan.gif" ALIGN="left" STYLE="padding-left:8pt">
				<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="325" HEIGHT="250">
				<TR>
					<TD WIDTH="350" HEIGHT="30">&nbsp;</TD>
				</TR>
				<!-- EXAM PLAN Part -->
				<TR>
					<TD WIDTH="350" HEIGHT="25" STYLE="border-bottom-width:1; border-bottom-color:rgb(204,204,204); border-bottom-style:dotted;">&nbsp;</TD>
				</TR>
				<TR>
					<TD WIDTH="350" HEIGHT="25" STYLE="border-bottom-width:1; border-bottom-color:rgb(204,204,204); border-bottom-style:dotted;">&nbsp;</TD>
				</TR>
				<TR>
					<TD WIDTH="350" HEIGHT="25" STYLE="border-bottom-width:1; border-bottom-color:rgb(204,204,204); border-bottom-style:dotted;">&nbsp;</TD>
				</TR>
				<TR>
					<TD WIDTH="350" HEIGHT="25" STYLE="border-bottom-width:1; border-bottom-color:rgb(204,204,204); border-bottom-style:dotted;">&nbsp;</TD>
				</TR>
				<TR>
					<TD WIDTH="350" HEIGHT="25" STYLE="border-bottom-width:1; border-bottom-color:rgb(204,204,204); border-bottom-style:dotted;">&nbsp;</TD>
				</TR>
				<TR>
					<TD WIDTH="350" HEIGHT="25" STYLE="border-bottom-width:1; border-bottom-color:rgb(204,204,204); border-bottom-style:dotted;">&nbsp;</TD>
				</TR>
				<TR>
					<TD WIDTH="350" HEIGHT="25" STYLE="border-bottom-width:1; border-bottom-color:rgb(204,204,204); border-bottom-style:dotted;">&nbsp;</TD>
				</TR>
				<TR>
					<TD WIDTH="350" HEIGHT="25" STYLE="border-bottom-width:1; border-bottom-color:rgb(204,204,204); border-bottom-style:dotted;">&nbsp;</TD>
				</TR>
				<!-- EXAM PLAN Part End -->
				<TR>
					<TD WIDTH="350" HEIGHT="20">&nbsp;</TD>
				</TR>
				</TABLE>
			</TD>
		</TR>
		</TABLE>
	</TD>
</TR>
<TR>
	<TD WIDTH="800" COLSPAN="2" HEIGHT="50"><IMG SRC="<?=IMG_URL?>site/main/bottom.gif" WIDTH="800" HEIGHT="50" BORDER="0"></TD>
</TR>
</TABLE>
</BODY>
</HTML>
<?
ob_end_flush();
?>
