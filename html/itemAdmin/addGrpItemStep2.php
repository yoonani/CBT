<?php
/* =============================================================================
File : itemAdmin/addGrpItemStep2.php
================================================================================
Coded by yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2006. 5. 
================================================================================
Dsec.
	���ٰ��� : A
	Group ���׿� ���ο� Item�� �߰��ϴ� �κ�
	- POST
	  = getItemInputType : �߰��� ������ ����, N Form���� ���� Y �ܺ�����
	  = getSubject  : ��������
	  = getFormat 	: ��������
	  = getCategory : ��������
	  = getLevel  	: ��������
	  = getMeSH 	: ��������
	  = getOptType 	: ������ ���� 
	  = getOptNo 	: ������ ����
	  = examID	: ����ID
	  = myGrpID	: GroupID
	  = isIndep	: �ܵ����׿���, N Group ����, Y �ܵ�����
	  = myPg	: ���躰 ���׺��� Page�� ������ ��ȣ
============================================================================= */
require_once("../../include/conf.php");
require_once(MY_INCLUDE . "header.php");

if( !$fnc->checkLevel($_SESSION["Level"], array("A")) ) {
	$fnc->alertBack("������ �� ���� �����Դϴ�.");
	exit;
}
?>

<TABLE border="0" width="590" cellpadding="0" cellspacing="0">
<FORM NAME="getItemStep2" ACTION="<?=URL_ROOT?>itemAdmin/procGrpItem.php" METHOD="POST" ENCTYPE="multipart/form-data">
	<tr>
		<td clspan="2"><img src="<?=IMG_URL?>site/examadmin/title_8.gif" border="0"></td>
	</tr>
	<TR>
<?
// �����Է� ����� FORM�̸�
if($_POST["getItemInputType"] == "N") {
?>
		<TD class="htitle" width="160" align="center">�� ��</TD>
		<TD class="htop" width="430">
<!--
        ADD FOR FCKeditor Test
        2006. 06.28 Bio
-->
<?
//
// BIO's FCKeditor Test
//
require_once "../FCKeditor/fckeditor.php";
$myRootPath = URL_ROOT."FCKeditor/";
$myFCKeditor = new FCKeditor('getContents');
$myFCKeditor->BasePath = $myRootPath ;
$myFCKeditor->Height    = 300 ;
$myFCKeditor->Width    = 400 ;
$myFCKeditor->ToolbarSet    = 'myMenu';
$myFCKeditor->Config['FlashUploadURL'] = $myRootPath . 'editor/filemanager/upload/php/Flash/upload_flash.php?Type=Flash&examID='.trim($_POST['examID']);
$myFCKeditor->Config['ImageUploadURL'] = $myRootPath . 'editor/filemanager/upload/php/upload.php?Type=Image&examID='.trim($_POST['examID']);
$myFCKeditor->Config['SkinPath'] = $myRootPath . 'editor/skins/default/' ; // ��Ų ����� �ּ� ���� 'office2003','default','silver'
$myFCKeditor->Value    = '';
$myFCKeditor->Create() ;
?>
<!--
        ADD FOR FCKeditor Test End
-->
		</TD>
	</TR>
	<TR>
		<TD class="htitle" align="center">Image</TD>
		<TD class="hmiddle"><INPUT TYPE="FILE" NAME="getImage"></TD>
	</TR>
	<TR>
		<TD class="htitle" align="center">Movie</TD>
		<TD class="hbottom"><INPUT TYPE="FILE" NAME="getMovie"></TD>
	</TR>
</TABLE>
<?



// �ܴ����� �ƴϸ� ���� ������ ���´�.
	if($_POST["getFormat"] != "D") {
?>
<br>
<!-- ���� �Է� //-->
<TABLE width="590" border="0" cellpadding="0" cellspacing="0" style="border-bottom:solid 1px #6F81AB;border-top:solid 1px #6F81AB">
<?
		function prtTField($name) {
			echo "<INPUT TYPE=\"TEXT\" NAME=\"" . $name . "\">";
		}
		function prtFile($name) {
			echo "<INPUT TYPE=\"FILE\" NAME=\"" . $name . "\">";
		}
		function prtRadio($name, $value) {
			echo "<INPUT TYPE=\"RADIO\" NAME=\"" . $name . "\" VALUE=\"" . $value . "\" class=\"radio\">";
		}
		function prtCBox($name, $value) {
			echo "<INPUT TYPE=\"CHECKBOX\" NAME=\"" . $name . "\" VALUE=\"" . $value . "\" class=\"radio\">";
		}



		for($i=0; $i < $_POST["getOptNo"]; $i++) {	
		// for���� ���ؼ� ���� ������ ���� ��������.
?>
	<TR>
		<TD width="150" align="center" class="htitle">����<?=$i+1?></TD>
		<TD width="440" style="padding-left:15px">
<?
			// ���� ���� ������ ���� Form�� �����ش�.
			$myNo = $i+1;
			$myTFiledName = "getOptTF" . $myNo;
			$myIMGName = "getOptIMG" . $myNo;



			if($_POST["getFormat"] == "A" || $_POST["getFormat"] == "K") {
				$myCor = "prtRadio";
				$myVal = "myCorrect";
			} else if ($_POST["getFormat"] == "R") {
				$myCor = "prtCBox";
				$myVal = "myCorrect[]";
			}



			switch($_POST["getOptType"]) {
				// TEXT�� �����ٶ�
				case "A" :
					$myCor($myVal, $myNo) . prtTField($myTFiledName);
					break;
				// Image�� �����ٶ�
				case "B" :
					$myCor($myVal, $myNo) . prtFile($myIMGName);
					break;
				// TEXT + Image
				case "C" :
					$myCor($myVal, $myNo) . prtTField($myTFiledName);
					echo "<br />";
					echo "IMG : ";
					prtFile($myIMGName);
					break;
			}
?>
		</TD>
	</TR>
<?
		} // ���� �Է� ��
		echo "</TABLE>";
	} // �ܴ��� �����̸� ���Ⱑ ǥ�õ��� �ʴ´�



} else {



// ���� �Է� ����� Form �� �ƴϸ� ���� ���ε� â�� ���δ�.
?>
<br>
<TABLE width="590" border="0" cellpadding="0" cellspacing="0">
	<TR>
		<TD class="htitle" width="150" align="center">File</TD>
		<TD width="440" style="border-top:solid 1px #6F81AB;0px;border-bottom:solid 1px #6F81AB;padding-left:15px"><INPUT TYPE="FILE" NAME="getFile"></TD>
	</TR>
</TABLE>
<?
}



?>
<br>
<TABLE width="590" border="0" cellpadding="0" cellspacing="0">
	<TR>
		<TD width="150" align="center" class="htitle">����</TD>
		<TD width="440" style="border-top:solid 1px #6F81AB;0px;border-bottom:solid 1px #6F81AB;padding-left:15px"><INPUT TYPE="TEXT" NAME="myScore"></TD>
	</TR>
<?



if($_POST["getFormat"] == "D" || $_POST["getItemInputType"] == "Y") {
?>
	<TR>
		<TD width="150" align="center" class="htitle">����</TD>
		<TD width="440" style="border-top:solid 1px #6F81AB;0px;border-bottom:solid 1px #6F81AB;padding-left:15px"><INPUT TYPE="TEXT" NAME="myCorrect"><BR />������ ���� ,(comma)�� ����</TD>
	</TR>
<?
}



?>
</TABLE>
<br>
<TABLE ALIGN="CENTER" width="590" border="0" cellpadding="0" cellspacing="0">
<TR>
	<TD><INPUT TYPE="submit"></TD>
</TR>
<INPUT TYPE="HIDDEN" NAME="examID" VALUE="<?=$_POST["examID"]?>">
<INPUT TYPE="HIDDEN" NAME="myGrpID" VALUE="<?=$_POST["myGrpID"]?>">
<INPUT TYPE="HIDDEN" NAME="isIndep" VALUE="<?=$_POST["isIndep"]?>">
<INPUT TYPE="HIDDEN" NAME="myPg" VALUE="<?=$_POST["myPg"]?>">
<INPUT TYPE="HIDDEN" NAME="getItemInputType" VALUE="<?=$_POST["getItemInputType"]?>">
<INPUT TYPE="HIDDEN" NAME="getSubject" VALUE="<?=$_POST["getSubject"]?>">
<INPUT TYPE="HIDDEN" NAME="getFormat" VALUE="<?=$_POST["getFormat"]?>">
<INPUT TYPE="HIDDEN" NAME="getCategory" VALUE="<?=$_POST["getCategory"]?>">
<INPUT TYPE="HIDDEN" NAME="getLevel" VALUE="<?=$_POST["getLevel"]?>">
<INPUT TYPE="HIDDEN" NAME="getMeSH" VALUE="<?=$_POST["getMeSH"]?>">
<INPUT TYPE="HIDDEN" NAME="getOptType" VALUE="<?=$_POST["getOptType"]?>">
<INPUT TYPE="HIDDEN" NAME="getOptNo" VALUE="<?=$_POST["getOptNo"]?>">
</TABLE>
</FORM>
<?
require_once (MY_INCLUDE . "closing.php");
?>
