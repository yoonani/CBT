<?php
/* =============================================================================
File : itemAdmin/addGroup.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	접근가능 : A
	문항 Group 생성
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");

if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("접근할 수 없는 권한입니다.");
	exit;
}

?>
<TABLE border="0" ALIGN="CENTER" width="590" cellpadding="0" cellspacing="0"> 
<FORM NAME="getGroup" METHOD="POST" ACTION="<?=URL_ROOT?>itemAdmin/procAddGroup.php" ENCTYPE="multipart/form-data">
	<tr>
		<td colspan="2"><img src="<?=IMG_URL?>site/examadmin/title_16.gif"></td>
	</tr>
	<TR>
		<TD align="center" width="150" class="htitle">Group문항</TD>
		<TD width="440" class="htop">
<!-- 주석처리 FOR FCKeditor Test
     2006. 06.21 Bio
	<TEXTAREA NAME="getContents" ROWS="10" COLS="42"></TEXTAREA>
-->
<?
//
// BIO's FCKeditor Test
//
require_once ("../FCKeditor/fckeditor.php");
$myRootPath = URL_ROOT . 'FCKeditor/';
$myFCKeditor = new FCKeditor('getContents');
$myFCKeditor->BasePath = $myRootPath ;
$myFCKeditor->Height    = 300 ;
$myFCKeditor->Width    = 420 ;
$myFCKeditor->ToolbarSet    = 'myMenu';
$myFCKeditor->Config['FlashUploadURL'] = $myRootPath . 'editor/filemanager/upload/php/Flash/upload_flash.php?Type=Flash&examID='.trim($_GET['examID']);
$myFCKeditor->Config['ImageUploadURL'] = $myRootPath . 'editor/filemanager/upload/php/upload.php?Type=Image&examID='.trim($_GET['examID']);
$myFCKeditor->Config['SkinPath'] = $myRootPath . 'editor/skins/default/' ; // 스킨 변경시 주석 해제 'office2003','default','silver'
$myFCKeditor->Value    = '';
$myFCKeditor->Create() ;
?>
		</TD>
	</TR>
</TABLE>
	<INPUT TYPE="HIDDEN" NAME="examID" VALUE="<?=$_GET["examID"]?>">
	<INPUT TYPE="HIDDEN" NAME="myPg" VALUE="<?=$_GET["myPg"]?>">
<TABLE width="590" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<TR>
	<TD colspan="2" align="right"><a href="javascript:getGroup.submit();"><img src="<?=IMG_URL?>site/icon/submit.gif"></a><!--<INPUT TYPE="SUBMIT" NAME="submit">--></TD>
</TR>
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
