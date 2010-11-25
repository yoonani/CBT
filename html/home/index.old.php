<?php
/* =============================================================================
File : home/index.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Desc.
	Log In 후 처음으로 이동하는 Page
	Session Level : A 전체관리자, B 학생, D 시험입력자, F : 학교별 관리자
	현재 Page는 전체 등급 접근가능
============================================================================= */
require_once ("../../include/conf.php");

//
// 레벨별 Menu 
//
require_once ("./conf.php");
?>
<LINK REL="stylesheet" TYPE="text/css" HREF="<?=URL_ROOT?>include/css/medcbt.css">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="800" height="600">
<TR>
<!-- Header Part -->
	<TD WIDTH="150" HEIGHT="100" BACKGROUND="<?=IMG_URL?>site/skin/top_left.gif"></TD>
	<TD WIDTH="650" HEIGHT="100" BACKGROUND="<?=IMG_URL?>site/skin/top_right.gif"></TD>
<!-- Header Part End -->
</TR>
<TR>
	<TD WIDTH="150" HEIGHT="50" BACKGROUND="<?=IMG_URL?>site/skin/top_left2.gif"></TD>
	<TD WIDTH="650" HEIGHT="50" BACKGROUND="<?=IMG_URL?>site/skin/top_right2.gif"></TD>
</TR>
<TR>
	<TD WIDTH="150" VALIGN="TOP" BACKGROUND="<?=IMG_URL?>site/skin/menu_bg.gif">
		<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="150">
		<TR>
			<TD WIDTH="150" HEIGHT="100">
<!-- User Info Part -->
				<TABLE BORDER="0" WIDTH="150" HEIGHT="100" CELLPADDING="0" CELLSPACING="0" BACKGROUND="<?=IMG_URL?>site/skin/userinfo_bg.gif">
				<TR>
					<TD WIDTH="150" HEIGHT="15">
						&nbsp;
					</TD>
				</TR>
				<TR>
					<TD WIDTH="150" HEIGHT="15" align="center" class="userinfo">
						<FONT FACE="굴림체"><?=trim($_SESSION['Name'])?>(<?=trim($_SESSION['ID'])?>)</FONT>
					</TD>
				</TR>
				<TR>
					<TD WIDTH="150" HEIGHT="15" align="center" class="userinfo">
						<FONT FACE="굴림체"><?=trim($levelInfo['position'])?></FONT>
					</TD>
				</TR>
				<TR>
					<TD WIDTH="150" HEIGHT="15" align="center" class="userinfo">
						<FONT FACE="굴림체"><?=trim($levelInfo['univ'])?></FONT>
					</TD>
				</TR>
				<TR>
					<TD WIDTH="150" HEIGHT="40" VALIGN="TOP" align="center" style="padding-left:7pt">
						<A HREF="<?=URL_ROOT?>joinModi.php"><IMG SRC="<?=IMG_URL?>site/skin/userinfo_modi.gif" BORDER="0"></A><A HREF="<?=URL_ROOT?>logout.php"><IMG SRC="<?=IMG_URL?>site/skin/logout_button.gif" BORDER="0"></A>
					</TD>
				</TR>
				</TABLE>
<!-- User Info Part End -->
			</TD>
		</TR>
<!-- Menu Part -->
<?
for($i=0;$i<count($levelInfo['menu']);$i++){;
?>
		<TR>
			<TD WIDTH="150" HEIGHT="30" ALIGN="RIGHT" BACKGROUND="<?=IMG_URL?>site/skin/menu_contents_bg.gif" class="menu"><?=trim($levelInfo['menu'][$i])?>&nbsp;&nbsp;&nbsp;</TD>
		</TR>
<?
}
?>
<!-- Menu Part End -->
		</TABLE>
	</TD>
<!-- Body Part -->
	<TD WIDTH="650" VALIGN="TOP" BACKGROUND="<?=IMG_URL?>site/skin/main_bg.gif" class="main"><A HREF='<?=URL_ROOT?>admin/index.php'>관리자 Page</A></TD>
<!-- Body Part End -->
</TR>
<TR>
<!-- Bottom Part -->
	<TD WIDTH="800" HEIGHT="70" COLSPAN="2"><IMG SRC="<?=IMG_URL?>site/skin/bottom.gif" WIDTH="800" HEIGHT="100" BORDER="0"></TD>
<!-- Bottom Part End -->
</TR>
</TABLE>
